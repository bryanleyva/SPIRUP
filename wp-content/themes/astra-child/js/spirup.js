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

/* ==========================================================================
   Acceso: pestanas "Iniciar sesion / Crear cuenta" (Mi cuenta y ventana del
   checkout) + ventana emergente del checkout: el cliente llena el formulario
   como invitado y, al pulsar "Realizar el pedido", entra o crea su cuenta;
   despues el pedido se reenvia solo con lo que ya escribio.
   ========================================================================== */
( function () {
	// Pestanas (sirven para cualquier .spirup-auth__tabs de la pagina)
	document.addEventListener( 'click', function ( e ) {
		var tab = e.target.closest( '[data-spirup-tab]' );
		if ( ! tab ) { return; }
		var root = tab.closest( '.spirup-auth__card' ) || document;
		var name = tab.getAttribute( 'data-spirup-tab' );
		root.querySelectorAll( '[data-spirup-tab]' ).forEach( function ( t ) { t.classList.toggle( 'is-active', t === tab ); } );
		root.querySelectorAll( '[data-spirup-panel]' ).forEach( function ( p ) { p.classList.toggle( 'is-active', p.getAttribute( 'data-spirup-panel' ) === name ); } );
		// Titulo y subtitulo propios de cada pestana (si la tarjeta los trae)
		var card = tab.closest( '.spirup-auth__card' );
		if ( card && card.getAttribute( 'data-title-' + name ) ) {
			var h = card.querySelector( '[data-spirup-auth-title]' );
			var sb = card.querySelector( '[data-spirup-auth-sub]' );
			if ( h ) { h.textContent = card.getAttribute( 'data-title-' + name ); }
			if ( sb ) { sb.textContent = card.getAttribute( 'data-sub-' + name ); }
		}
		var err = root.querySelector( '[data-spirup-auth-error]' );
		if ( err ) { err.hidden = true; }
	} );

	var modal = document.getElementById( 'spirup-authmodal' );
	if ( typeof window.SPIRUP_AUTH === 'undefined' ) { return; }

	var $ = window.jQuery;
	function errorBox( form ) {
		var root = form.closest( '.spirup-auth__card' ) || document;
		return root.querySelector( '[data-spirup-auth-error]' );
	}
	function showError( form, msg, field ) {
		var box = errorBox( form );
		if ( box ) { box.textContent = msg; box.hidden = false; box.scrollIntoView( { block: 'nearest' } ); }
		if ( field ) {
			var el = form.querySelector( '[name="' + field + '"]' );
			if ( el ) { el.focus(); }
		}
	}
	function switchTab( form, name ) {
		var root = form.closest( '.spirup-auth__card' ) || document;
		var t = root.querySelector( '.spirup-auth__tab[data-spirup-tab="' + name + '"]' );
		if ( t ) { t.click(); }
	}
	function field( name ) {
		var el = document.querySelector( 'form.checkout [name="' + name + '"]' );
		return el ? el.value : '';
	}

	// --- Ventana del checkout ------------------------------------------------
	if ( modal && typeof $ !== 'undefined' ) {
		var openModal = function () {
			var box = modal.querySelector( '[data-spirup-auth-error]' );
			if ( box ) { box.hidden = true; }
			var email = field( 'billing_email' );
			if ( email ) {
				[ '#sa-username', '#sa_email' ].forEach( function ( sel ) {
					var el = modal.querySelector( sel );
					if ( el && ! el.value ) { el.value = email; }
				} );
			}
			var nombre = ( field( 'billing_first_name' ) + ' ' + field( 'billing_last_name' ) ).trim();
			var elN = modal.querySelector( '#sa_name' );
			if ( elN && ! elN.value && nombre ) { elN.value = nombre; }
			var elT = modal.querySelector( '#sa_phone' );
			if ( elT && ! elT.value ) { elT.value = field( 'billing_phone' ); }
			modal.hidden = false;
			document.body.classList.add( 'spirup-authmodal-open' );
			var first = modal.querySelector( '.spirup-auth__panel.is-active input' );
			if ( first ) { window.setTimeout( function () { first.focus(); }, 50 ); }
		};
		var closeModal = function () {
			modal.hidden = true;
			document.body.classList.remove( 'spirup-authmodal-open' );
		};
		modal.querySelectorAll( '[data-spirup-auth-close]' ).forEach( function ( b ) { b.addEventListener( 'click', closeModal ); } );
		document.addEventListener( 'keydown', function ( e ) { if ( 'Escape' === e.key && ! modal.hidden ) { closeModal(); } } );

		// WooCommerce devuelve el error especial => abrimos la ventana en su lugar
		$( document.body ).on( 'checkout_error', function ( ev, message ) {
			var html = String( message || '' );
			var notice = document.querySelector( '.woocommerce-NoticeGroup-checkout, form.checkout .woocommerce-error' );
			var flagged = html.indexOf( 'data-spirup-auth' ) !== -1 || ( notice && notice.querySelector( '[data-spirup-auth]' ) );
			if ( ! flagged ) { return; }
			if ( notice ) { notice.remove(); }
			$( 'form.checkout' ).removeClass( 'processing' );
			openModal();
		} );
		modal.spirupClose = closeModal;
	}

	// --- Entrar / crear cuenta (ventana del checkout y pagina "Mi cuenta") ----
	document.querySelectorAll( '[data-spirup-auth-form]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var kind = form.getAttribute( 'data-spirup-auth-form' );
			var redirect = form.getAttribute( 'data-spirup-auth-redirect' );
			var btn  = form.querySelector( 'button[type="submit"]' );
			var data = new FormData( form );
			data.append( 'action', 'spirup_auth_' + kind );
			data.append( 'nonce', SPIRUP_AUTH.nonce );
			var box = errorBox( form );
			if ( box ) { box.hidden = true; }
			btn.disabled = true; btn.classList.add( 'is-loading' );

			fetch( SPIRUP_AUTH.ajax, { method: 'POST', credentials: 'same-origin', body: data } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					if ( ! res || ! res.success ) {
						var d = ( res && res.data ) || {};
						if ( d.switch ) { switchTab( form, d.switch ); }
						showError( form, d.message || 'No se pudo completar. Inténtalo de nuevo.', d.field );
						btn.disabled = false; btn.classList.remove( 'is-loading' );
						return;
					}
					// Fuera del checkout (Mi cuenta): recargar ya con la sesion abierta
					if ( redirect || ! modal || typeof $ === 'undefined' ) {
						window.location.href = redirect || window.location.href;
						return;
					}
					// En el checkout: nonce fresco (el navegador ya tiene la cookie)
					// y se reenvia el pedido con los datos que ya escribio.
					var nd = new FormData();
					nd.append( 'action', 'spirup_auth_nonce' );
					nd.append( 'nonce', SPIRUP_AUTH.nonce );
					return fetch( SPIRUP_AUTH.ajax, { method: 'POST', credentials: 'same-origin', body: nd } )
						.then( function ( r ) { return r.json(); } )
						.then( function ( n ) {
							if ( ! n || ! n.success ) { window.location.reload(); return; }
							var $form = $( 'form.checkout' );
							$form.find( 'input[name="woocommerce-process-checkout-nonce"], input[name="_wpnonce"]' ).val( n.data.nonce );
							document.body.classList.add( 'logged-in' );
							if ( modal.spirupClose ) { modal.spirupClose(); }
							var old = document.querySelector( '.woocommerce-NoticeGroup-checkout, form.checkout .woocommerce-error' );
							if ( old ) { old.remove(); }
							$form.trigger( 'submit' );
						} );
				} )
				.catch( function () {
					showError( form, 'Hubo un problema de conexión. Inténtalo de nuevo.' );
					btn.disabled = false; btn.classList.remove( 'is-loading' );
				} );
		} );
	} );
} )();
