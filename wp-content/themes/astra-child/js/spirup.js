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
	var stage = document.querySelector( '[data-splash-video]' );
	if ( ! stage ) { return; }
	var vid = stage.querySelector( 'video' );
	if ( ! vid ) { return; }

	/* Ping-pong entre MIN y MAX (sin llegar al inicio vacio ni al final quieto):
	   asi el agua nunca se detiene. */
	var MIN_FRAC = 0.20, MAX_FRAC = 0.82;
	function dur() { return ( vid.duration && isFinite( vid.duration ) && vid.duration > 0 ) ? vid.duration : 2; }
	function minT() { return dur() * MIN_FRAC; }
	function maxT() { return dur() * MAX_FRAC; }

	var mode = 'fwd', lastTs = 0, running = false;
	function tick( ts ) {
		if ( ! running ) { return; }
		if ( ! lastTs ) { lastTs = ts; }
		var dt = ( ts - lastTs ) / 1000; lastTs = ts;
		if ( mode === 'fwd' ) {
			if ( vid.currentTime >= maxT() ) {   // reversa ANTES de que se quede quieto
				mode = 'rev';
				try { vid.pause(); } catch ( e ) {}
			}
		} else {
			var t = vid.currentTime - dt;         // reversa a 1x
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
		try {
			vid.currentTime = minT();
			var p = vid.play();
			if ( p && p.catch ) { p.catch( function () {} ); }
		} catch ( e ) {}
		mode = 'fwd'; lastTs = 0;
		requestAnimationFrame( tick );
	}
	if ( ! ( 'IntersectionObserver' in window ) ) { start(); return; }
	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( en ) {
			if ( en.isIntersecting ) {
				start();
				io.disconnect();
			}
		} );
	}, { threshold: 0.35 } );
	io.observe( stage );
} )();

/* ===== Showcase de sabores: selector Citrus Blue / Rebel Blue ===== */
( function () {
	var sc = document.querySelector( '.spirup-showcase' );
	if ( ! sc ) { return; }
	var wm  = sc.querySelector( '[data-wm]' );
	var ext = sc.querySelector( '[data-extractos]' );
	var tabs = sc.querySelectorAll( '[data-flavor-set]' );
	var data = {
		citrus: { wm: 'CITRUS BLUE', ext: 'El poder de la naturaleza en el limón y la hierba luisa' },
		rebel:  { wm: 'REBEL BLUE',  ext: 'El poder de la naturaleza en el blueberry y el limón' }
	};
	Array.prototype.forEach.call( tabs, function ( t ) {
		t.addEventListener( 'click', function () {
			var f = t.getAttribute( 'data-flavor-set' );
			if ( ! data[ f ] ) { return; }
			sc.setAttribute( 'data-flavor', f );
			if ( wm )  { wm.textContent  = data[ f ].wm; }
			if ( ext ) { ext.textContent = data[ f ].ext; }
			Array.prototype.forEach.call( tabs, function ( x ) {
				var on = ( x === t );
				x.classList.toggle( 'is-active', on );
				x.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			} );
		} );
	} );
} )();
