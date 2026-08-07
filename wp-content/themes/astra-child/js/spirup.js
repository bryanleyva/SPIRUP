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
