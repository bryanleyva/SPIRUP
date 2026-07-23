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
