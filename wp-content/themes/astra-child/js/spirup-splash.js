/**
 * SPIRUP - Ola de agua SIMULADA por detras de la lata (Parte 2).
 *
 * El agua no es una foto: es una simulacion. Cuando la seccion entra en
 * pantalla rompe una ola por detras de la lata, y despues sigue rompiendo en
 * ciclo. Toda el agua vive por detras: nunca pasa por delante del envase.
 *
 * COMO SE CONSTRUYE EL AGUA
 * -------------------------
 *   1. FISICA (CPU). Cada particula tiene x, y y tambien z, con z=0 en el
 *      plano de la lata. La ola se emite desde una LINEA horizontal por detras
 *      -no desde un punto-, que es lo que da una pared de agua en vez de un
 *      surtidor. Una envolvente decide donde se levanta la cresta, y un sesgo
 *      reparte el empuje: la mayoria del agua se queda abajo formando el
 *      cuerpo y solo una parte sale disparada como cresta y espuma.
 *   2. PERSPECTIVA. Al proyectar, cada particula se escala por
 *      FOCAL / (FOCAL - z). Como la ola avanza un poco en z segun se levanta,
 *      la cresta se adelanta y da la sensacion de que voltea.
 *   3. CAMPO DE DENSIDAD (GPU, 1er pase). Cada particula se pinta como una
 *      campana gaussiana sumandose sobre un framebuffer. Donde varias se
 *      solapan la densidad crece: eso es lo que funde las gotas sueltas en
 *      laminas de liquido continuas -metaballs-.
 *   4. SUPERFICIE (GPU, 2o pase). Del campo se saca el gradiente y de ahi la
 *      normal. Con eso se calculan el filo del agua, los reflejos y el grosor.
 *      La lamina fina queda casi blanca y transparente y solo el volumen tira
 *      al turquesa de la lata: el agua no tiene color propio, lo coge del
 *      espesor.
 *
 * Si el navegador no da WebGL se muestra la foto de respaldo
 * (imagenes/splash-agua.png), que sigue en el marcado.
 *
 * Marcado sobre el que se monta (ya existe en front-page.php):
 *
 *   <div class="spirup-parte2__stage" data-water-stage>
 *     <div class="spirup-parte2__water" data-splash-src="..."></div>
 *     <img class="spirup-parte2__can" ...>
 *   </div>
 *
 * Arranca cuando js/spirup.js le agrega .is-in al stage (IntersectionObserver).
 *
 * Ajustes desde el HTML, sin tocar este archivo:
 *   data-splash-src="<url sin extension>"  foto de respaldo (.webp / .png)
 *   data-splash-spread="3.3"     ancho de la escena, en anchos de lata
 *   data-splash-x="0.50"         donde cae la ola, sobre el ancho de la lata
 *   data-splash-y="0.60"         donde cae la ola, sobre el alto de la lata
 *   data-splash-jet="1"          fuerza de la ola
 *   data-splash-flow="1"         agua de fondo entre ola y ola
 *   data-splash-shine="1"        intensidad de los reflejos
 *   data-splash-tint="#62d3d6"   color del agua (el aqua de la lata)
 *   data-splash-manual           no lo monta el auto-arranque (banco de pruebas)
 */
window.SpirupSplash = ( function () {
	'use strict';

	/* ===================== shaders ===================== */

	/* 1er pase: cada particula es un quad con una gaussiana, sumandose. */
	var VS_BLOB = [
		'attribute vec2 aCenter;',
		'attribute vec2 aCorner;',
		'attribute vec2 aVel;',
		'attribute float aRadius;',
		'attribute float aWeight;',
		'attribute float aSharp;',
		'uniform float uAspect;',
		'varying vec2 vLocal;',
		'varying float vWeight;',
		'varying float vSharp;',
		'void main(){',
		// la gota se alarga en la direccion en que viaja: eso convierte las
		// bolas en trazos, y los trazos son lo que se lee como chorro
		'  float sp = length( aVel );',
		'  vec2 dir = sp > 0.0001 ? aVel / sp : vec2( 1.0, 0.0 );',
		'  vec2 per = vec2( -dir.y, dir.x );',
		'  float st = 1.0 + min( sp * 0.85, 1.7 );',
		'  vec2 off = ( dir * aCorner.x * st + per * aCorner.y ) * aRadius;',
		'  vec2 p = aCenter * 2.0 - 1.0 + off * vec2( 2.0, 2.0 * uAspect );',
		'  gl_Position = vec4( p, 0.0, 1.0 );',
		'  vLocal = aCorner;',
		'  vWeight = aWeight;',
		'  vSharp = aSharp;',
		'}'
	].join( '\n' );

	var FS_BLOB = [
		'precision mediump float;',
		'varying vec2 vLocal;',
		'varying float vWeight;',
		'varying float vSharp;',
		'void main(){',
		'  float r2 = dot( vLocal, vLocal );',
		'  if ( r2 > 1.0 ) discard;',
		// vSharp bajo = campana ancha y blanda: asi se dibuja lo que esta
		// fuera de foco, muy cerca de la camara
		'  float d = max( exp( -vSharp * r2 ) - 0.018, 0.0 );',
		'  gl_FragColor = vec4( d * vWeight, 0.0, 0.0, 1.0 );',
		'}'
	].join( '\n' );

	/* 2o pase: de la densidad a la superficie del liquido. */
	var VS_QUAD = [
		'attribute vec2 aPos;',
		'varying vec2 vUv;',
		'void main(){',
		'  vUv = aPos * 0.5 + 0.5;',
		'  gl_Position = vec4( aPos, 0.0, 1.0 );',
		'}'
	].join( '\n' );

	var FS_WATER = [
		'precision mediump float;',
		'uniform sampler2D uField;',
		'uniform vec2  uTexel;',
		'uniform vec3  uTint;',
		'uniform vec3  uDeep;',
		'uniform float uShine;',
		'uniform float uGain;',
		'varying vec2 vUv;',
		'float D( vec2 uv ){ return texture2D( uField, uv ).r * uGain; }',
		'void main(){',
		'  float d = D( vUv );',
		'  float a = smoothstep( 0.44, 0.90, d );',
		// orla exterior: una banda tenue por fuera del cuerpo. Es lo que separa
		// el agua del fondo y le da el aire salpicado, fresco
		'  float halo = smoothstep( 0.24, 0.50, d ) - a;',
		'  if ( a + halo <= 0.003 ) discard;',
		'  float dx = D( vUv + vec2( uTexel.x, 0.0 ) ) - D( vUv - vec2( uTexel.x, 0.0 ) );',
		'  float dy = D( vUv + vec2( 0.0, uTexel.y ) ) - D( vUv - vec2( 0.0, uTexel.y ) );',
		// el gradiente crudo es diminuto: sin amplificarlo la normal sale casi
		// plana, no hay reflejos y el agua queda como una mancha
		'  vec2 g = vec2( dx, dy ) * 12.0;',
		'  vec3 n = normalize( vec3( -g, 1.0 ) );',
		'  float rim = smoothstep( 0.10, 0.85, length( g ) );',
		'  float thick = smoothstep( 0.58, 2.80, d );',
		'  vec3 L  = normalize( vec3( -0.48, 0.70, 0.53 ) );',
		'  vec3 L2 = normalize( vec3(  0.62, 0.42, 0.66 ) );',
		'  float diff = max( dot( n, L ), 0.0 );',
		'  float rl = max( reflect( -L, n ).z, 0.0 );',
		// tres reflejos: uno ancho que da el mojado, una chispa estrecha que da
		// el frescor, y un relleno desde el otro lado para que no quede plano
		'  float spec    = pow( rl, 10.0 ) * 0.55;',
		'  float sparkle = pow( rl, 64.0 ) * 1.00;',
		'  float spec2   = pow( max( reflect( -L2, n ).z, 0.0 ), 22.0 ) * 0.35;',
		'  vec3 col = mix( uTint, uDeep, thick * 0.88 );',
		'  col *= 0.92 + 0.30 * diff;',
		'  col = mix( col, vec3( 1.0 ), clamp( rim * 0.28 + ( spec + spec2 + sparkle ) * uShine, 0.0, 1.0 ) );',
		// dispersion en el filo: el borde tira a frio por un lado y a calido por
		// el otro, como el canto de un vidrio. Poco, pero se nota
		'  col.r *= 1.0 + n.x * 0.10 * rim;',
		'  col.b *= 1.0 - n.x * 0.10 * rim;',
		'  col = mix( vec3( dot( col, vec3( 0.299, 0.587, 0.114 ) ) ), col, 1.08 );',
		'  float alpha = a * clamp( 0.15 + rim * 0.56 + thick * 0.36 + ( spec + sparkle ) * uShine * 0.80, 0.0, 1.0 );',
		'  alpha += halo * 0.20;',
		'  gl_FragColor = vec4( col, clamp( alpha, 0.0, 1.0 ) );',
		'}'
	].join( '\n' );

	/* ===================== utilidades ===================== */

	function attr( el, name, fallback ) {
		var v = el.getAttribute( name );
		return ( v === null || v === '' ) ? fallback : v;
	}
	function num( el, name, fallback ) {
		var v = parseFloat( el.getAttribute( name ) );
		return isNaN( v ) ? fallback : v;
	}
	function hex2rgb( h ) {
		h = ( h || '' ).replace( '#', '' );
		if ( h.length === 3 ) { h = h[0] + h[0] + h[1] + h[1] + h[2] + h[2]; }
		if ( ! /^[0-9a-f]{6}$/i.test( h ) ) { h = '62d3d6'; }
		return [ parseInt( h.substr( 0, 2 ), 16 ) / 255,
			parseInt( h.substr( 2, 2 ), 16 ) / 255,
			parseInt( h.substr( 4, 2 ), 16 ) / 255 ];
	}

	function shader( gl, type, src ) {
		var s = gl.createShader( type );
		gl.shaderSource( s, src );
		gl.compileShader( s );
		if ( ! gl.getShaderParameter( s, gl.COMPILE_STATUS ) ) {
			gl.deleteShader( s );
			return null;
		}
		return s;
	}
	function program( gl, vsSrc, fsSrc ) {
		var vs = shader( gl, gl.VERTEX_SHADER, vsSrc );
		var fs = shader( gl, gl.FRAGMENT_SHADER, fsSrc );
		if ( ! vs || ! fs ) { return null; }
		var p = gl.createProgram();
		gl.attachShader( p, vs );
		gl.attachShader( p, fs );
		gl.linkProgram( p );
		if ( ! gl.getProgramParameter( p, gl.LINK_STATUS ) ) { return null; }
		return p;
	}

	/* Generador con semilla: con la misma semilla el chorro sale igual, lo que
	   permite comparar fotogramas al calibrar. */
	function rngFrom( seed ) {
		var s = seed >>> 0;
		return function () {
			s = ( s + 0x6D2B79F5 ) >>> 0;
			var t = Math.imul( s ^ ( s >>> 15 ), 1 | s );
			t = ( t + Math.imul( t ^ ( t >>> 7 ), 61 | t ) ) ^ t;
			return ( ( t ^ ( t >>> 14 ) ) >>> 0 ) / 4294967296;
		};
	}

	var MAXPX = 1000;   // tope del buffer visible (hay dos planos que pintar)
	var MINDT = 1 / 90; // paso maximo de fisica, para que no explote tras una pausa
	var FOCAL = 1.45;   // distancia focal de la proyeccion

	/* ===================== instancia ===================== */

	function create( stage, opts ) {
		opts = opts || {};
		var mount = opts.mount || stage.querySelector( '.spirup-parte2__water' );
		var can   = opts.can   || stage.querySelector( '.spirup-parte2__can' );
		if ( ! mount || ! can ) { return null; }

		var CFG = {
			src:      opts.src      || attr( mount, 'data-splash-src', 'imagenes/splash-agua' ),
			spread:   opts.spread   || num( mount, 'data-splash-spread', 3.3 ),
			x:        opts.x        || num( mount, 'data-splash-x', 0.50 ),
			y:        opts.y        || num( mount, 'data-splash-y', 0.60 ),
			jet:      opts.jet   != null ? opts.jet   : num( mount, 'data-splash-jet', 1 ),
			flow:     opts.flow  != null ? opts.flow  : num( mount, 'data-splash-flow', 1 ),
			shine:    opts.shine != null ? opts.shine : num( mount, 'data-splash-shine', 1 ),
			tint:     opts.tint     || attr( mount, 'data-splash-tint', '#62d3d6' ),
			ratio:    opts.ratio    || num( mount, 'data-splash-ratio', 0.80 )
		};

		/* ---------- capas ----------
		   Dos planos: el de detras vive dentro de .spirup-parte2__water (z-index
		   1) y el de delante cuelga del stage con z-index 3, por encima de la
		   lata. Asi el agua la envuelve. */
		var boxBack = document.createElement( 'div' );
		boxBack.className = 'spirup-splash';
		boxBack.setAttribute( 'aria-hidden', 'true' );

		// respaldo: la foto, por si no hay WebGL
		var pic = document.createElement( 'picture' );
		var srcWebp = document.createElement( 'source' );
		srcWebp.type = 'image/webp';
		srcWebp.srcset = CFG.src + '.webp';
		var img = document.createElement( 'img' );
		img.className = 'spirup-splash__img';
		img.src = CFG.src + '.png';
		img.alt = '';
		pic.appendChild( srcWebp );
		pic.appendChild( img );

		var cvBack = document.createElement( 'canvas' );
		cvBack.className = 'spirup-splash__cv';
		boxBack.appendChild( pic );
		boxBack.appendChild( cvBack );
		mount.appendChild( boxBack );

		function ctxOf( c ) {
			try {
				return c.getContext( 'webgl', { alpha: true, premultipliedAlpha: false, antialias: false, depth: false } )
					|| c.getContext( 'experimental-webgl', { alpha: true, premultipliedAlpha: false, antialias: false, depth: false } );
			} catch ( e ) { return null; }
		}

		/* Un plano = un canvas con su contexto, sus programas y su framebuffer.
		   Son contextos independientes: WebGL no comparte recursos entre ellos. */
		function makePlane( cv ) {
			var gl = ctxOf( cv );
			if ( ! gl ) { return null; }
			var pBlob = program( gl, VS_BLOB, FS_BLOB );
			var pWater = program( gl, VS_QUAD, FS_WATER );
			if ( ! pBlob || ! pWater ) { return null; }

			var pl = {
				cv: cv, gl: gl, pBlob: pBlob, pWater: pWater,
				loc: {
					center: gl.getAttribLocation( pBlob, 'aCenter' ),
					corner: gl.getAttribLocation( pBlob, 'aCorner' ),
					vel:    gl.getAttribLocation( pBlob, 'aVel' ),
					radius: gl.getAttribLocation( pBlob, 'aRadius' ),
					weight: gl.getAttribLocation( pBlob, 'aWeight' ),
					sharp:  gl.getAttribLocation( pBlob, 'aSharp' ),
					aspect: gl.getUniformLocation( pBlob, 'uAspect' ),
					pos:   gl.getAttribLocation( pWater, 'aPos' ),
					field: gl.getUniformLocation( pWater, 'uField' ),
					texel: gl.getUniformLocation( pWater, 'uTexel' ),
					tint:  gl.getUniformLocation( pWater, 'uTint' ),
					deep:  gl.getUniformLocation( pWater, 'uDeep' ),
					shine: gl.getUniformLocation( pWater, 'uShine' ),
					gain:  gl.getUniformLocation( pWater, 'uGain' )
				},
				quad: gl.createBuffer(),
				blob: gl.createBuffer(),
				tex: gl.createTexture(),
				fb: gl.createFramebuffer(),
				fbW: 0, fbH: 0
			};
			gl.bindBuffer( gl.ARRAY_BUFFER, pl.quad );
			gl.bufferData( gl.ARRAY_BUFFER, new Float32Array( [ -1, -1, 3, -1, -1, 3 ] ), gl.STATIC_DRAW );

			/* El campo necesita rango: si la suma de particulas se corta en 1.0
			   el interior queda plano y sin gradiente, y sin gradiente no hay ni
			   filo ni reflejos. Con media coma flotante hay rango de sobra; si
			   no la hay, se emite mas flojo y se reescala al leer. */
			var eHF = gl.getExtension( 'OES_texture_half_float' );
			var eHFL = gl.getExtension( 'OES_texture_half_float_linear' );
			pl.field = ( eHF && eHFL )
				? { type: eHF.HALF_FLOAT_OES, gain: 1, weight: 0.55 }
				: { type: gl.UNSIGNED_BYTE, gain: 4.2, weight: 0.13 };
			return pl;
		}

		function resizeField( pl, w, h ) {
			var gl = pl.gl;
			pl.fbW = Math.max( 2, Math.round( w * 0.5 ) );
			pl.fbH = Math.max( 2, Math.round( h * 0.5 ) );
			gl.bindTexture( gl.TEXTURE_2D, pl.tex );
			gl.texImage2D( gl.TEXTURE_2D, 0, gl.RGBA, pl.fbW, pl.fbH, 0, gl.RGBA, pl.field.type, null );
			gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR );
			gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR );
			gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE );
			gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE );
			gl.bindFramebuffer( gl.FRAMEBUFFER, pl.fb );
			gl.framebufferTexture2D( gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, pl.tex, 0 );
			if ( pl.field.type !== gl.UNSIGNED_BYTE
				&& gl.checkFramebufferStatus( gl.FRAMEBUFFER ) !== gl.FRAMEBUFFER_COMPLETE ) {
				pl.field = { type: gl.UNSIGNED_BYTE, gain: 4.2, weight: 0.13 };
				gl.texImage2D( gl.TEXTURE_2D, 0, gl.RGBA, pl.fbW, pl.fbH, 0, gl.RGBA, pl.field.type, null );
				gl.framebufferTexture2D( gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, pl.tex, 0 );
			}
			gl.bindFramebuffer( gl.FRAMEBUFFER, null );
		}

		var back = makePlane( cvBack );
		if ( ! back ) {
			boxBack.classList.add( 'is-static' );   // sin WebGL: se muestra la foto
			return {
				play: function () {}, layout: function () {},
				setConfig: function () {}, el: boxBack, img: img, webgl: false
			};
		}

		/* ---------- particulas ----------
		   x, y en 0..1 sobre la escena (y hacia abajo). z = 0 en el plano de la
		   lata y crece hacia el espectador. */
		var MAXP = 1500;
		var px = new Float32Array( MAXP ), py = new Float32Array( MAXP ), pz = new Float32Array( MAXP );
		var vx = new Float32Array( MAXP ), vy = new Float32Array( MAXP ), vz = new Float32Array( MAXP );
		var pr = new Float32Array( MAXP ), pd = new Float32Array( MAXP );
		var pl_ = new Float32Array( MAXP ), plMax = new Float32Array( MAXP );
		var alive = new Uint8Array( MAXP );
		var vBack = new Float32Array( MAXP * 6 * 9 );
		var rnd = rngFrom( 1 );

		/* La ola se emite desde una LINEA horizontal por detras de la lata, no
		   desde un punto: eso es lo que da una pared de agua en vez de un
		   surtidor. La cresta se levanta, se adelanta y rompe hacia delante. */
		var EMIT = { y: 0.78, z: -0.44 };
		var WAVE_PERIOD = 2.2;    // cada cuanto rompe una ola nueva
		var WAVE_EMIT   = 1.10;   // cuanto dura el frente de rotura

		function spawnWave( u, tt ) {
			var i;
			for ( i = 0; i < MAXP; i++ ) { if ( ! alive[ i ] ) { break; } }
			if ( i >= MAXP ) { return; }

			var drop = rnd() < 0.26;                   // una de cada cuatro, gota suelta
			// envolvente: la cresta se levanta en un punto y muere en los lados.
			// El exponente sobre u la corre a un lado: una ola simetrica no
			// parece una ola
			var env = Math.pow( Math.sin( Math.PI * Math.pow( u, 1.30 ) ), 0.85 );
			// sesgo hacia abajo: la mayoria apenas se levanta -el cuerpo de la
			// ola- y unas pocas salen disparadas -la cresta y la espuma-
			var lm   = Math.pow( rnd(), 1.25 );
			var lift = ( 0.14 + 0.72 * env ) * CFG.jet * ( 0.32 + 0.85 * lm );

			px[ i ] = 0.14 + u * 0.72 + ( rnd() - 0.5 ) * 0.03;
			py[ i ] = EMIT.y + ( rnd() - 0.5 ) * 0.05;
			pz[ i ] = EMIT.z + rnd() * 0.18;
			vx[ i ] = ( u - 0.5 ) * 0.52 * CFG.jet + ( rnd() - 0.5 ) * 0.10;
			vy[ i ] = -lift * ( 0.80 + 0.32 * ( 1 - tt ) );
			// la cresta se adelanta segun se levanta: eso es lo que la hace voltear
			vz[ i ] = ( 0.03 + 0.26 * env ) * CFG.jet * ( 0.55 + 0.85 * tt ) * ( 0.6 + 0.7 * lm );
			// lo que mas sube sale mas fino: eso es espuma, no cuerpo de agua
			pr[ i ] = ( drop ? 0.008 + rnd() * 0.011 : 0.026 + rnd() * 0.030 ) * ( 1 - 0.35 * lm );
			pd[ i ] = drop ? 3.0 : 1;                  // la gota pesa mas por su tamano
			plMax[ i ] = 1.35 + rnd() * 0.75;
			pl_[ i ] = plMax[ i ];
			alive[ i ] = 1;
		}

		var emitAcc = 0, bgAcc = 0, wavePhase = 0, simT = 0;

		function step( dt ) {
			simT += dt;
			wavePhase += dt;
			if ( wavePhase >= WAVE_PERIOD ) { wavePhase -= WAVE_PERIOD; }

			if ( wavePhase < WAVE_EMIT ) {
				var tt = wavePhase / WAVE_EMIT;
				emitAcc += 950 * CFG.jet * dt;
				while ( emitAcc >= 1 ) {
					// el frente de rotura recorre la cresta: a cada punto le toca
					// romper un poco mas tarde que al anterior
					var u = rnd();
					if ( tt >= u * 0.42 ) { spawnWave( u, tt ); }
					emitAcc -= 1;
				}
			} else {
				emitAcc = 0;
			}

			// corriente de fondo: entre ola y ola el agua no se seca del todo
			bgAcc += 115 * CFG.flow * dt;
			while ( bgAcc >= 1 ) { spawnWave( rnd(), 0.5 ); bgAcc -= 1; }

			var g = 1.02, drag = Math.pow( 0.88, dt );
			for ( var i = 0; i < MAXP; i++ ) {
				if ( ! alive[ i ] ) { continue; }
				// turbulencia: sin ella la cresta sale como un molde, toda igual
				vx[ i ] += Math.sin( py[ i ] * 19.0 + simT * 2.3 + i ) * 0.09 * dt;
				vz[ i ] += Math.cos( px[ i ] * 15.0 + simT * 1.7 + i ) * 0.05 * dt;
				vy[ i ] += g * dt;
				vx[ i ] *= drag; vy[ i ] *= drag; vz[ i ] *= drag;
				px[ i ] += vx[ i ] * dt;
				py[ i ] += vy[ i ] * dt;
				pz[ i ] += vz[ i ] * dt;
				pl_[ i ] -= dt;
				// nunca pasa del plano de la lata: el agua se queda detras
				if ( pl_[ i ] <= 0 || py[ i ] > 1.25 || pz[ i ] > 0.06 ) { alive[ i ] = 0; }
			}
		}

		var CORN = [ -1, -1, 1, -1, -1, 1, -1, 1, 1, -1, 1, 1 ];

		/* Proyecta en perspectiva y llena el buffer. Todo va al mismo plano: el
		   agua vive por detras de la lata. */
		function fillVerts() {
			var n = 0, o = 0;
			for ( var i = 0; i < MAXP; i++ ) {
				if ( ! alive[ i ] ) { continue; }
				var z = pz[ i ];

				// la gota nace y muere encogiendo: sin esto aparecen y se cortan
				var life = pl_[ i ] / plMax[ i ];
				var grow = Math.min( 1, ( 1 - life ) * 7 );
				var fade = Math.min( 1, life * 3.2 );
				var soft = Math.min( grow, fade );
				if ( soft <= 0.02 ) { continue; }

				var sc = FOCAL / ( FOCAL - z );            // perspectiva
				var sx = 0.5 + ( px[ i ] - 0.5 ) * sc;
				var sy = 0.5 + ( py[ i ] - 0.5 ) * sc;
				if ( sx < -0.35 || sx > 1.35 || sy < -0.35 || sy > 1.35 ) { continue; }

				var r = pr[ i ] * sc * soft;
				if ( r <= 0.0006 ) { continue; }
				// lo mas alejado pierde definicion: campana algo mas blanda
				var sharp = 4.0 / ( 1 + ( sc - 1 ) * 1.15 );
				var w = back.field.weight * pd[ i ] * ( 0.62 + 0.50 * soft ) / Math.pow( sc, 1.30 );

				var cx = sx, cy = 1 - sy;                  // a coordenadas de GL (y arriba)
				var wx = vx[ i ] * sc, wy = -vy[ i ] * sc;
				for ( var k = 0; k < 6; k++ ) {
					vBack[ o++ ] = cx;
					vBack[ o++ ] = cy;
					vBack[ o++ ] = CORN[ k * 2 ];
					vBack[ o++ ] = CORN[ k * 2 + 1 ];
					vBack[ o++ ] = wx;
					vBack[ o++ ] = wy;
					vBack[ o++ ] = r;
					vBack[ o++ ] = w;
					vBack[ o++ ] = sharp;
				}
				n += 6;
			}
			return n;
		}

		var TINT, SURF, DEEP;
		function palette() {
			TINT = hex2rgb( CFG.tint );
			// superficie: casi blanca, es donde el agua apenas tiene espesor
			SURF = [ TINT[0] + ( 1 - TINT[0] ) * 0.58,
				TINT[1] + ( 1 - TINT[1] ) * 0.58,
				TINT[2] + ( 1 - TINT[2] ) * 0.58 ];
			// fondo: el turquesa de la lata, que es lo que se ve por el volumen
			DEEP = [ TINT[0] * 0.62, TINT[1] * 0.90, TINT[2] * 0.94 ];
		}
		palette();

		function drawPlane( pl, verts, nVerts ) {
			var gl = pl.gl, L = pl.loc;
			var w = pl.cv.width, h = pl.cv.height;
			if ( ! w ) { return; }

			// --- 1er pase: campo de densidad ---
			gl.bindFramebuffer( gl.FRAMEBUFFER, pl.fb );
			gl.viewport( 0, 0, pl.fbW, pl.fbH );
			gl.clearColor( 0, 0, 0, 1 );
			gl.clear( gl.COLOR_BUFFER_BIT );
			if ( nVerts ) {
				gl.enable( gl.BLEND );
				gl.blendFunc( gl.ONE, gl.ONE );          // se suman: eso funde las gotas
				gl.useProgram( pl.pBlob );
				gl.uniform1f( L.aspect, w / h );
				gl.bindBuffer( gl.ARRAY_BUFFER, pl.blob );
				gl.bufferData( gl.ARRAY_BUFFER, verts.subarray( 0, nVerts * 9 ), gl.DYNAMIC_DRAW );
				var S = 9 * 4;
				gl.enableVertexAttribArray( L.center );
				gl.vertexAttribPointer( L.center, 2, gl.FLOAT, false, S, 0 );
				gl.enableVertexAttribArray( L.corner );
				gl.vertexAttribPointer( L.corner, 2, gl.FLOAT, false, S, 8 );
				gl.enableVertexAttribArray( L.vel );
				gl.vertexAttribPointer( L.vel, 2, gl.FLOAT, false, S, 16 );
				gl.enableVertexAttribArray( L.radius );
				gl.vertexAttribPointer( L.radius, 1, gl.FLOAT, false, S, 24 );
				gl.enableVertexAttribArray( L.weight );
				gl.vertexAttribPointer( L.weight, 1, gl.FLOAT, false, S, 28 );
				gl.enableVertexAttribArray( L.sharp );
				gl.vertexAttribPointer( L.sharp, 1, gl.FLOAT, false, S, 32 );
				gl.drawArrays( gl.TRIANGLES, 0, nVerts );
				gl.disableVertexAttribArray( L.center );
				gl.disableVertexAttribArray( L.corner );
				gl.disableVertexAttribArray( L.vel );
				gl.disableVertexAttribArray( L.radius );
				gl.disableVertexAttribArray( L.weight );
				gl.disableVertexAttribArray( L.sharp );
			}

			// --- 2o pase: la superficie del liquido ---
			gl.bindFramebuffer( gl.FRAMEBUFFER, null );
			gl.viewport( 0, 0, w, h );
			gl.clearColor( 0, 0, 0, 0 );
			gl.clear( gl.COLOR_BUFFER_BIT );
			gl.enable( gl.BLEND );
			gl.blendFunc( gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA );
			gl.useProgram( pl.pWater );
			gl.activeTexture( gl.TEXTURE0 );
			gl.bindTexture( gl.TEXTURE_2D, pl.tex );
			gl.uniform1i( L.field, 0 );
			gl.uniform2f( L.texel, 1 / pl.fbW, 1 / pl.fbH );
			gl.uniform3f( L.tint, SURF[0], SURF[1], SURF[2] );
			gl.uniform3f( L.deep, DEEP[0], DEEP[1], DEEP[2] );
			gl.uniform1f( L.shine, CFG.shine );
			gl.uniform1f( L.gain, pl.field.gain );
			gl.bindBuffer( gl.ARRAY_BUFFER, pl.quad );
			gl.enableVertexAttribArray( L.pos );
			gl.vertexAttribPointer( L.pos, 2, gl.FLOAT, false, 0, 0 );
			gl.drawArrays( gl.TRIANGLES, 0, 3 );
			gl.disableVertexAttribArray( L.pos );
		}

		function render() {
			drawPlane( back, vBack, fillVerts() );
		}

		/* ---------- encuadre ----------
		   La escena se dimensiona respecto de la LATA, no del stage, asi la
		   proporcion agua/lata es la misma en cualquier pantalla. */
		function layout() {
			var sRect = stage.getBoundingClientRect();
			var cRect = can.getBoundingClientRect();
			if ( ! cRect.width ) { return; }

			// en movil la escena completa seria mas ancha que la pantalla
			var w = Math.min( cRect.width * CFG.spread, window.innerWidth * 1.02 );
			var h = w * CFG.ratio;
			var cx = cRect.left + cRect.width  * CFG.x - sRect.left;
			var cy = cRect.top  + cRect.height * CFG.y - sRect.top;
			var left = cx - w / 2, top = cy - h * EMIT.y;

			boxBack.style.width  = w + 'px';
			boxBack.style.height = h + 'px';
			boxBack.style.left   = left + 'px';
			boxBack.style.top    = top + 'px';

			var dpr = Math.min( window.devicePixelRatio || 1, 2 );
			var pw  = Math.min( MAXPX, Math.round( w * dpr ) );
			var ph  = Math.round( pw * CFG.ratio );
			back.cv.width = pw;
			back.cv.height = ph;
			resizeField( back, pw, ph );
			render();
		}

		/* ---------- bucle ---------- */
		var raf = null, last = 0, played = false, visible = true, running = false;

		function loop( now ) {
			raf = requestAnimationFrame( loop );
			var dt = last ? ( now - last ) / 1000 : MINDT;
			last = now;
			if ( dt > 0.1 ) { dt = 0.1; }        // tras una pausa, no dar un salto
			// pasos fijos: la fisica se rompe con dt grandes
			while ( dt > 0 ) {
				var s = Math.min( dt, MINDT );
				step( s );
				dt -= s;
			}
			render();
		}

		function startLoop() {
			if ( running ) { return; }
			running = true; last = 0;
			raf = requestAnimationFrame( loop );
		}
		function stopLoop() {
			running = false;
			if ( raf ) { cancelAnimationFrame( raf ); raf = null; }
		}

		function play( force ) {
			if ( played && ! force ) { return; }
			played = true;
			layout();

			if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
				boxBack.classList.add( 'is-static' );   // foto quieta, sin simulacion
				stopLoop();
				return;
			}
			if ( force ) {
				boxBack.classList.remove( 'is-static' );
				alive.fill( 0 ); emitAcc = 0; bgAcc = 0; simT = 0;
				rnd = rngFrom( 1 );
			}
			wavePhase = 0;
			startLoop();
		}

		/* El bucle no tiene por que correr cuando la seccion no se ve. */
		if ( window.IntersectionObserver ) {
			new IntersectionObserver( function ( entries ) {
				visible = entries[ 0 ].isIntersecting;
				if ( ! played ) { return; }
				if ( visible ) { startLoop(); } else { stopLoop(); }
			}, { threshold: 0 } ).observe( stage );
		}
		document.addEventListener( 'visibilitychange', function () {
			if ( ! played ) { return; }
			if ( document.hidden ) { stopLoop(); } else if ( visible ) { startLoop(); }
		} );

		var rt;
		window.addEventListener( 'resize', function () {
			clearTimeout( rt );
			rt = setTimeout( layout, 120 );
		} );

		return {
			play: play,
			layout: layout,
			el: boxBack,
			img: img,
			webgl: true,
			/* avanza la simulacion un tiempo fijo y dibuja: para calibrar */
			simulate: function ( seconds ) {
				alive.fill( 0 ); emitAcc = 0; bgAcc = 0; simT = 0; wavePhase = 0;
				rnd = rngFrom( 1 );
				var n = Math.round( seconds / MINDT );
				for ( var i = 0; i < n; i++ ) { step( MINDT ); }
				render();
			},
			setConfig: function ( o ) {
				for ( var k in o ) {
					if ( o.hasOwnProperty( k ) ) { CFG[ k ] = o[ k ]; }
				}
				palette();
				layout();
			}
		};
	}

	return { create: create };
} )();

/* ---------------------------------------------------------------------------
   Auto-montaje en la portada. Espera a que js/spirup.js marque el stage con
   .is-in (IntersectionObserver); si ya venia marcado, arranca de una.
   --------------------------------------------------------------------------- */
( function () {
	'use strict';

	var stage = document.querySelector( '[data-water-stage]' );
	if ( ! stage ) { return; }
	var mount = stage.querySelector( '.spirup-parte2__water' );
	// data-splash-manual = lo monta otro script (lata-demo.html): no duplicar
	if ( ! mount || mount.hasAttribute( 'data-splash-manual' ) ) { return; }

	var splash = window.SpirupSplash.create( stage );
	if ( ! splash ) { return; }

	if ( stage.classList.contains( 'is-in' ) ) {
		splash.play();
	} else if ( window.MutationObserver ) {
		var mo = new MutationObserver( function () {
			if ( stage.classList.contains( 'is-in' ) ) { mo.disconnect(); splash.play(); }
		} );
		mo.observe( stage, { attributes: true, attributeFilter: [ 'class' ] } );
	} else {
		splash.play();
	}
} )();
