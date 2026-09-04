/**
 * SPIRUP - interacciones del tema.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var burger = document.querySelector( '.spirup-burger' );
		var nav = document.getElementById( 'spirup-nav' );
		if ( ! burger || ! nav ) {
			return;
		}

		function closeMenu() {
			nav.classList.remove( 'is-open' );
			burger.classList.remove( 'is-open' );
			burger.setAttribute( 'aria-expanded', 'false' );
		}

		burger.addEventListener( 'click', function () {
			var open = nav.classList.toggle( 'is-open' );
			burger.classList.toggle( 'is-open', open );
			burger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );

		// Cerrar al pulsar un enlace del menu.
		nav.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', closeMenu );
		} );

		// Cerrar al volver a escritorio.
		window.addEventListener( 'resize', function () {
			if ( window.innerWidth > 900 ) {
				closeMenu();
			}
		} );
	} );
} )();

/* ==========================================================================
   Carrito lateral (drawer) + WooCommerce
   ========================================================================== */
( function () {
	'use strict';
	var cart = document.getElementById( 'spirup-cart' );
	if ( ! cart || typeof SPIRUP_CART === 'undefined' ) {
		return;
	}
	function openCart() {
		cart.hidden = false;
		document.body.style.overflow = 'hidden';
		requestAnimationFrame( function () { cart.classList.add( 'is-open' ); } );
	}
	function closeCart() {
		cart.classList.remove( 'is-open' );
		document.body.style.overflow = '';
		setTimeout( function () { cart.hidden = true; }, 300 );
	}

	// Abrir con el icono del carrito; cerrar con X / overlay / "Seguir comprando".
	document.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( '[data-spirup-cart-open]' ) ) {
			e.preventDefault();
			openCart();
		} else if ( e.target.closest( '#spirup-cart [data-act="close"]' ) ) {
			e.preventDefault();
			closeCart();
		}
	} );
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! cart.hidden ) { closeCart(); }
	} );

	function setCount( n ) {
		var els = document.querySelectorAll( '.spirup-cart-count' );
		for ( var i = 0; i < els.length; i++ ) {
			els[ i ].textContent = n;
			els[ i ].classList.toggle( 'is-visible', n > 0 );
		}
	}

	// Cantidad +/- y eliminar (delegado, via AJAX).
	cart.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '[data-act="inc"],[data-act="dec"],[data-act="remove"]' );
		if ( ! btn ) { return; }
		var item = btn.closest( '.spirup-cart__item' );
		if ( ! item ) { return; }
		cart.classList.add( 'is-loading' );
		var body = 'action=spirup_cart_update' +
			'&nonce=' + encodeURIComponent( SPIRUP_CART.nonce ) +
			'&key=' + encodeURIComponent( item.getAttribute( 'data-key' ) ) +
			'&act=' + encodeURIComponent( btn.getAttribute( 'data-act' ) );
		fetch( SPIRUP_CART.ajax, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body
		} )
			.then( function ( r ) { return r.json(); } )
			.then( function ( d ) {
				var box = document.getElementById( 'spirup-cart-inner' );
				if ( box ) { box.innerHTML = d.html; }
				setCount( d.count );
				cart.classList.remove( 'is-loading' );
			} )
			.catch( function () { cart.classList.remove( 'is-loading' ); } );
	} );

	// Abrir el drawer automaticamente al añadir un producto (evento WooCommerce).
	if ( window.jQuery ) {
		jQuery( document.body ).on( 'added_to_cart', function () { openCart(); } );
	}
} )();

/* ==========================================================================
   Navbar: se OCULTA al bajar el scroll y APARECE al subir.
   ========================================================================== */
( function () {
	'use strict';
	var sticky = document.querySelector( '.spirup-sticky' );
	if ( ! sticky ) { return; }
	var last = window.pageYOffset || 0;
	var TH = 6;          // umbral para ignorar micro-movimientos
	var TOP = 80;        // cerca del tope siempre se muestra
	var ticking = false;

	function update() {
		var y = window.pageYOffset || 0;
		if ( y < TOP ) {
			sticky.classList.remove( 'is-hidden' );      // arriba del todo: visible
		} else if ( y - last > TH ) {
			sticky.classList.add( 'is-hidden' );          // bajando: ocultar
		} else if ( last - y > TH ) {
			sticky.classList.remove( 'is-hidden' );       // subiendo: mostrar
		}
		last = y;
		ticking = false;
	}

	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			window.requestAnimationFrame( update );
			ticking = true;
		}
	}, { passive: true } );
} )();

/* ==========================================================================
   Parte 2: el agua (video) arranca al entrar en pantalla y hace PING-PONG:
   adelante -> reversa (sin volver al inicio vacio) -> adelante... asi nunca
   para y el agua siempre esta presente y en movimiento.
   ========================================================================== */
( function () {
	'use strict';
	var sc = document.querySelector( '.spirup-showcase' );
	var stages = document.querySelectorAll( '[data-splash-video]' );
	if ( ! stages.length ) { return; }

	/* Ping-pong por cada video (cada slide tiene el suyo): entre MIN y MAX, sin
	   llegar al inicio vacio ni al final quieto => el agua nunca se detiene. */
	var MIN_FRAC = 0.20, MAX_FRAC = 0.82;
	var starters = [];
	Array.prototype.forEach.call( stages, function ( stage ) {
		var vid = stage.querySelector( 'video' );
		if ( ! vid ) { return; }
		function dur() { return ( vid.duration && isFinite( vid.duration ) && vid.duration > 0 ) ? vid.duration : 2; }
		function minT() { return dur() * MIN_FRAC; }
		function maxT() { return dur() * MAX_FRAC; }
		var mode = 'fwd', lastTs = 0, running = false;
		function tick( ts ) {
			if ( ! running ) { return; }
			if ( ! lastTs ) { lastTs = ts; }
			var dt = ( ts - lastTs ) / 1000; lastTs = ts;
			if ( mode === 'fwd' ) {
				if ( vid.currentTime >= maxT() ) { mode = 'rev'; try { vid.pause(); } catch ( e ) {} }
			} else {
				var t = vid.currentTime - dt;
				if ( t <= minT() ) {
					try { vid.currentTime = minT(); } catch ( e ) {}
					mode = 'fwd';
					var p = vid.play(); if ( p && p.catch ) { p.catch( function () {} ); }
				} else {
					try { vid.currentTime = t; } catch ( e ) {}
				}
			}
			requestAnimationFrame( tick );
		}
		function start() {
			if ( running ) { return; }
			running = true;
			stage.classList.add( 'is-playing' );
			try { vid.currentTime = minT(); var p = vid.play(); if ( p && p.catch ) { p.catch( function () {} ); } } catch ( e ) {}
			mode = 'fwd'; lastTs = 0;
			requestAnimationFrame( tick );
		}
		starters.push( start );
	} );
	function startAll() { starters.forEach( function ( f ) { f(); } ); }

	var target = sc || stages[0];
	if ( ! ( 'IntersectionObserver' in window ) ) { startAll(); return; }
	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( en ) { if ( en.isIntersecting ) { startAll(); io.disconnect(); } } );
	}, { threshold: 0.2 } );
	io.observe( target );
} )();

/* ===== Showcase = carrusel: las flechas DESLIZAN el slide del sabor =====
   El entrante llega desde la derecha (next) o la izquierda (prev) y cubre al
   anterior, que sale por el lado opuesto. Actualiza data-flavor (color de flechas). */
( function () {
	'use strict';
	var sc = document.querySelector( '.spirup-showcase' );
	if ( ! sc ) { return; }
	var slides = sc.querySelectorAll( '.spirup-showcase__slide' );
	if ( slides.length < 2 ) { return; }
	var order = [], map = {};
	Array.prototype.forEach.call( slides, function ( s ) {
		var k = s.getAttribute( 'data-flavor-slide' );
		order.push( k ); map[ k ] = s;
	} );
	var current = sc.getAttribute( 'data-flavor' ) || order[0];
	Array.prototype.forEach.call( slides, function ( s ) {
		s.style.transform = ( s.getAttribute( 'data-flavor-slide' ) === current ) ? 'translateX(0)' : 'translateX(100%)';
	} );

	var animating = false;
	function go( dir ) {
		if ( animating ) { return; }
		var i = order.indexOf( current );
		if ( i < 0 ) { i = 0; }
		var nextKey = order[ ( i + dir + order.length ) % order.length ];
		if ( nextKey === current ) { return; }
		animating = true;
		var incoming = map[ nextKey ], outgoing = map[ current ];
		var fromX = dir > 0 ? '100%' : '-100%';   // next: desde la derecha; prev: desde la izquierda
		var outX  = dir > 0 ? '-100%' : '100%';
		incoming.style.transition = 'none';
		incoming.style.transform = 'translateX(' + fromX + ')';
		void incoming.offsetWidth;                // reflow para que arranque desde fromX
		incoming.style.transition = '';
		incoming.style.transform = 'translateX(0)';
		outgoing.style.transform = 'translateX(' + outX + ')';
		sc.setAttribute( 'data-flavor', nextKey );
		current = nextKey;
		window.setTimeout( function () {
			outgoing.style.transition = 'none';
			outgoing.style.transform = 'translateX(100%)';  // aparcado a la derecha, listo
			void outgoing.offsetWidth;
			outgoing.style.transition = '';
			animating = false;
		}, 520 );
	}
	Array.prototype.forEach.call( sc.querySelectorAll( '[data-flavor-prev]' ), function ( b ) {
		b.addEventListener( 'click', function () { go( -1 ); } );
	} );
	Array.prototype.forEach.call( sc.querySelectorAll( '[data-flavor-next]' ), function ( b ) {
		b.addEventListener( 'click', function () { go( 1 ); } );
	} );
} )();

/* ==========================================================================
   Navegacion por anclas (menu: Beneficios / Conocenos / Productos ...)
   En desktop el contenido con esos id vive en .spirup-mobileflow (oculto), y el
   real esta horneado en .spirup-bloque con anclas [data-jump]. Este modulo salta
   a la ancla VISIBLE (segun breakpoint) y descuenta la altura de la cabecera fija.
   ========================================================================== */
( function () {
	'use strict';
	function headerOffset() {
		var s = document.querySelector( '.spirup-sticky' );
		return ( s ? s.getBoundingClientRect().height : 100 ) + 14;
	}
	function isVisible( el ) {
		return !! ( el && ( el.offsetParent !== null || el.getClientRects().length ) );
	}
	function findTarget( hash ) {
		if ( ! hash ) { return null; }
		var list = [];
		try { list = document.querySelectorAll( '#' + ( window.CSS && CSS.escape ? CSS.escape( hash ) : hash ) + ', [data-jump="' + hash + '"]' ); } catch ( e ) {}
		for ( var i = 0; i < list.length; i++ ) { if ( isVisible( list[ i ] ) ) { return list[ i ]; } }
		return document.getElementById( hash );
	}
	function scrollToHash( hash, smooth ) {
		var el = findTarget( hash );
		if ( ! el ) { return false; }
		var y = el.getBoundingClientRect().top + ( window.pageYOffset || 0 ) - headerOffset();
		window.scrollTo( { top: Math.max( 0, Math.round( y ) ), behavior: smooth ? 'smooth' : 'auto' } );
		return true;
	}

	// Click en un enlace de esta misma pagina que apunte a una ancla.
	document.addEventListener( 'click', function ( e ) {
		var a = e.target.closest ? e.target.closest( 'a[href*="#"]' ) : null;
		if ( ! a ) { return; }
		var href = a.getAttribute( 'href' ) || '';
		var hash = href.split( '#' )[ 1 ];
		if ( ! hash ) { return; }
		// Solo si el enlace es de ESTA pagina (mismo path) o una ancla suelta.
		var samePage = ( href.charAt( 0 ) === '#' ) || ( a.pathname === window.location.pathname );
		if ( ! samePage ) { return; }
		if ( document.getElementById( hash ) || document.querySelector( '[data-jump="' + hash + '"]' ) ) {
			if ( scrollToHash( hash, true ) ) {
				e.preventDefault();
				if ( window.history && history.pushState ) { history.pushState( null, '', '#' + hash ); }
			}
		}
	} );

	// Al cargar con #hash (llegando desde otra pagina): corrige el salto nativo.
	if ( window.location.hash && window.location.hash.length > 1 ) {
		var h = window.location.hash.slice( 1 );
		window.addEventListener( 'load', function () {
			window.setTimeout( function () { scrollToHash( h, false ); }, 80 );
		} );
	}
} )();
