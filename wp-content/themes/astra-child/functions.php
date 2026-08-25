<?php
/**
 * SPIRUP Child - functions.php
 *
 * Punto de entrada del child theme de Astra.
 * Aqui codificamos toda la logica en PHP puro.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Evita el acceso directo.
}

/**
 * Cargar estilos y fuentes.
 */
function spirup_enqueue_styles() {

	// Fuentes (Google Fonts, gratis):
	//  - Montserrat  -> cuerpo de texto
	//  - Manrope     -> titulos (alternativa gratuita cercana a PP Neue Montreal)
	//  - Kaushan Script -> banner de inicio (alternativa gratuita tipo Northwell)
	wp_enqueue_style(
		'spirup-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Manrope:wght@600;700;800&family=Kaushan+Script&display=swap',
		array(),
		null
	);

	// Estilo del tema padre Astra.
	wp_enqueue_style(
		'astra-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'astra' )->get( 'Version' )
	);

	// Estilo del child (SPIRUP). filemtime = cache-busting en desarrollo.
	$child_css = get_stylesheet_directory() . '/style.css';
	wp_enqueue_style(
		'spirup-child-style',
		get_stylesheet_uri(),
		array( 'astra-parent-style', 'spirup-fonts' ),
		file_exists( $child_css ) ? filemtime( $child_css ) : '1.0.0'
	);

	// Scripts de WooCommerce: añadir al carrito por AJAX + fragments del drawer.
	if ( function_exists( 'WC' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );
	}

	// JS del tema (menu movil, carrito lateral, etc.).
	$child_js = get_stylesheet_directory() . '/js/spirup.js';
	wp_enqueue_script(
		'spirup-js',
		get_stylesheet_directory_uri() . '/js/spirup.js',
		array( 'jquery' ),
		file_exists( $child_js ) ? filemtime( $child_js ) : '1.0.0',
		true
	);
	wp_localize_script(
		'spirup-js',
		'SPIRUP_CART',
		array(
			'ajax'  => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'spirup_cart' ),
		)
	);

	// Lata 3D (Three.js) - solo en la portada.
	if ( is_front_page() ) {
		// Splash de agua detras de la lata (Parte 2). Depende de spirup-js,
		// que es quien marca el stage con .is-in al entrar en pantalla.
		$splash_js = get_stylesheet_directory() . '/js/spirup-splash.js';
		wp_enqueue_script(
			'spirup-splash',
			get_stylesheet_directory_uri() . '/js/spirup-splash.js',
			array( 'spirup-js' ),
			file_exists( $splash_js ) ? filemtime( $splash_js ) : '1.0.0',
			true
		);

		wp_enqueue_script(
			'threejs',
			'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
			array(),
			'r128',
			true
		);

		$lata_js = get_stylesheet_directory() . '/js/spirup-lata3d.js';
		wp_enqueue_script(
			'spirup-lata3d',
			get_stylesheet_directory_uri() . '/js/spirup-lata3d.js',
			array( 'threejs' ),
			file_exists( $lata_js ) ? filemtime( $lata_js ) : '1.0.0',
			true
		);

		// URL de la textura del envoltorio de la lata.
		wp_localize_script(
			'spirup-lata3d',
			'SPIRUP_LATA',
			array(
				'wrapUrl' => get_stylesheet_directory_uri() . '/imagenes/lata-wrap.jpg',
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'spirup_enqueue_styles' );

/**
 * Favicon / icono del sitio: el rayo de SPIR UP en la pestana del navegador
 * (en vez del icono por defecto de WordPress).
 */
function spirup_favicon() {
	$icon = get_stylesheet_directory_uri() . '/imagenes/favicon.png';
	echo "\n<link rel=\"icon\" type=\"image/png\" href=\"" . esc_url( $icon ) . "\">\n";
	echo "<link rel=\"shortcut icon\" type=\"image/png\" href=\"" . esc_url( $icon ) . "\">\n";
	echo "<link rel=\"apple-touch-icon\" href=\"" . esc_url( $icon ) . "\">\n";
}
add_action( 'wp_head', 'spirup_favicon', 5 );
add_action( 'admin_head', 'spirup_favicon' );

/**
 * Anadir una clase identificadora al <body>.
 */
function spirup_body_class( $classes ) {
	$classes[] = 'astra-child';
	return $classes;
}
add_filter( 'body_class', 'spirup_body_class' );

/**
 * Crear automaticamente las paginas legales si no existen.
 *
 * Las paginas viven en la base de datos (no viajan por FTP). Esta funcion
 * las crea con el slug exacto para que sus plantillas page-*.php se apliquen
 * y los enlaces del footer funcionen en cualquier entorno (local o servidor).
 */
function spirup_ensure_pages() {
	if ( get_option( 'spirup_pages_v1' ) ) {
		return;
	}
	$pages = array(
		'politica-de-privacidad' => 'Política de privacidad',
		'terminos-y-condiciones' => 'Términos y condiciones',
		'libro-de-reclamaciones' => 'Libro de reclamaciones',
	);
	foreach ( $pages as $slug => $title ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => $title,
					'post_name'   => $slug,
				)
			);
		}
	}
	update_option( 'spirup_pages_v1', 1 );
}
add_action( 'init', 'spirup_ensure_pages' );

/* ==========================================================================
   WooCommerce: carrito lateral (drawer) + vistas de compra
   ========================================================================== */

// Soporte de WooCommerce en el tema.
add_action( 'after_setup_theme', function () {
	add_theme_support( 'woocommerce' );
} );

/**
 * Renderiza el contenido del carrito lateral (items + totales).
 */
function spirup_cart_drawer_content() {
	if ( ! function_exists( 'WC' ) || is_null( WC()->cart ) ) {
		return;
	}
	$cart = WC()->cart;
	?>
	<div class="spirup-cart__body">
		<?php if ( $cart->is_empty() ) : ?>
			<p class="spirup-cart__empty">Tu carrito está vacío.</p>
		<?php else : ?>
			<ul class="spirup-cart__items">
				<?php foreach ( $cart->get_cart() as $key => $item ) :
					$product = $item['data'];
					if ( ! $product || ! $product->exists() ) {
						continue;
					}
					$qty   = $item['quantity'];
					$thumb = $product->get_image( array( 64, 64 ) );
					?>
					<li class="spirup-cart__item" data-key="<?php echo esc_attr( $key ); ?>">
						<div class="spirup-cart__thumb"><?php echo $thumb; // phpcs:ignore ?></div>
						<div class="spirup-cart__info">
							<strong class="spirup-cart__name"><?php echo esc_html( $product->get_name() ); ?></strong>
							<span class="spirup-cart__price"><?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?></span>
							<div class="spirup-cart__qty">
								<button type="button" class="spirup-cart__qbtn" data-act="dec" aria-label="Quitar uno">&minus;</button>
								<input type="text" class="spirup-cart__qval" value="<?php echo esc_attr( $qty ); ?>" readonly>
								<button type="button" class="spirup-cart__qbtn" data-act="inc" aria-label="Añadir uno">+</button>
							</div>
						</div>
						<button type="button" class="spirup-cart__remove" data-act="remove" aria-label="Eliminar">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14M10 11v6M14 11v6"/></svg>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="spirup-cart__foot" data-empty="<?php echo $cart->is_empty() ? '1' : '0'; ?>">
		<?php if ( ! $cart->is_empty() ) : ?>
			<a class="spirup-cart__pay" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
				Pagar total <?php echo wp_kses_post( $cart->get_cart_total() ); ?>
			</a>
		<?php endif; ?>
		<button type="button" class="spirup-cart__keep" data-act="close">Seguir comprando</button>
	</div>
	<?php
}

// Actualizar el drawer via fragments de WooCommerce (al añadir al carrito).
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	ob_start();
	spirup_cart_drawer_content();
	$fragments['#spirup-cart-inner'] = '<div id="spirup-cart-inner">' . ob_get_clean() . '</div>';
	return $fragments;
} );

// Contador del icono del carrito (fragment).
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$fragments['.spirup-cart-count'] = '<span class="spirup-cart-count' . ( $count ? ' is-visible' : '' ) . '">' . esc_html( $count ) . '</span>';
	return $fragments;
} );

// AJAX: cambiar cantidad / eliminar item del carrito.
function spirup_cart_update() {
	check_ajax_referer( 'spirup_cart', 'nonce' );
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	$act = isset( $_POST['act'] ) ? sanitize_text_field( wp_unslash( $_POST['act'] ) ) : '';
	if ( $key && WC()->cart ) {
		$item = WC()->cart->get_cart_item( $key );
		if ( $item ) {
			if ( 'remove' === $act ) {
				WC()->cart->remove_cart_item( $key );
			} elseif ( 'inc' === $act ) {
				WC()->cart->set_quantity( $key, $item['quantity'] + 1 );
			} elseif ( 'dec' === $act ) {
				$new = $item['quantity'] - 1;
				if ( $new <= 0 ) {
					WC()->cart->remove_cart_item( $key );
				} else {
					WC()->cart->set_quantity( $key, $new );
				}
			}
		}
	}
	ob_start();
	spirup_cart_drawer_content();
	wp_send_json( array(
		'html'  => ob_get_clean(),
		'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
	) );
}
add_action( 'wp_ajax_spirup_cart_update', 'spirup_cart_update' );
add_action( 'wp_ajax_nopriv_spirup_cart_update', 'spirup_cart_update' );

/* ==========================================================================
   Catalogo simple: SIN paginas de producto individuales.
   Solo se puede anadir al carrito y pagar. Al abrir una URL /producto/... se
   vuelve al inicio (nunca se muestra la pagina de producto ni "tienda en obras").
   ========================================================================== */

// 1) Si se accede a una pagina de producto, redirigir a la seccion de productos.
//    Prioridad 1 = corre ANTES del "coming soon / tienda en obras" de WooCommerce.
add_action( 'template_redirect', function () {
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_safe_redirect( home_url( '/#productos' ) );
		exit;
	}
}, 1 );

// 2) Que los enlaces de producto de WooCommerce (imagen/titulo en cualquier loop)
//    no apunten a la pagina del producto, sino a la seccion de productos del inicio.
add_filter( 'woocommerce_loop_product_link', function () {
	return home_url( '/#productos' );
} );

/* ==========================================================================
   Checkout: forzar el CLASICO (el que disenamos: crema + teal), no el bloque
   nuevo de WooCommerce (la "pantalla rara" en ingles con el boton morado).
   ========================================================================== */
add_filter( 'the_content', function ( $content ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url() ) {
		return do_shortcode( '[woocommerce_checkout]' );
	}
	return $content;
}, 20 );

/**
 * A partir de aqui: hooks, custom post types, integracion WooCommerce,
 * shortcodes y demas logica del ecommerce SPIRUP.
 */
