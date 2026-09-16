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
	$icon = get_stylesheet_directory_uri() . '/imagenes/Recurso 13@4x-8 1.png';
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
	if ( get_option( 'spirup_pages_v2' ) ) {
		return;
	}
	$pages = array(
		'politica-de-privacidad' => 'Política de privacidad',
		'terminos-y-condiciones' => 'Términos y condiciones',
		'libro-de-reclamaciones' => 'Libro de reclamaciones',
		'producto'               => 'Producto',
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
	update_option( 'spirup_pages_v2', 1 );
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

/* ==========================================================================
   Acceso (login)
   - La pagina "Mi cuenta" usa la plantilla propia woocommerce/myaccount/form-login.php.
   - wp-login.php (el de WordPress) se viste con los colores y el logo de la marca.
   ========================================================================== */

// Logo de SPIR UP arriba del formulario de wp-login.php + colores de marca.
// (login_head con prioridad alta => sale DESPUES del css de WordPress y manda).
add_action( 'login_head', function () {
	$logo = get_stylesheet_directory_uri() . '/imagenes/logo-spirup@2x.png';   // alta resolucion: no pixela
	?>
	<style>
		body.login { background: #faf6ea; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
		.login h1 a {
			background-image: url('<?php echo esc_url( $logo ); ?>') !important;
			background-size: contain; width: 92px; height: 112px; margin-bottom: 10px;
		}
		.login form {
			background: #fff; border: 1px solid #ece2c9; border-radius: 22px;
			box-shadow: 0 18px 40px rgba(40, 60, 40, .08); padding: 28px 26px;
		}
		.login form .input, .login input[type="text"], .login input[type="password"] {
			border: 1.5px solid #d9cca8; border-radius: 12px; background: #fff;
			padding: 11px 14px; font-size: 15px; color: #17323B;
		}
		.login form .input:focus, .login input[type="text"]:focus, .login input[type="password"]:focus {
			border-color: #2f918f; box-shadow: 0 0 0 3px rgba(47, 145, 143, .15); outline: 0;
		}
		.login label { color: #17323B; font-weight: 600; }
		.wp-core-ui .button-primary {
			background: #1e7477; border-color: #1e7477; border-radius: 999px;
			padding: 4px 26px; height: auto; min-height: 44px; font-weight: 700; box-shadow: none; text-shadow: none;
		}
		.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus { background: #17595c; border-color: #17595c; box-shadow: none; }
		.login #nav a, .login #backtoblog a { color: #2f918f !important; }
		.login #nav a:hover, .login #backtoblog a:hover { color: #17595c !important; }
		.login .message, .login .notice, .login #login_error { border-left-color: #2f918f; border-radius: 10px; }
	</style>
	<?php
}, 100 );

// El logo del login lleva al inicio de la tienda (no a wordpress.org).
add_filter( 'login_headerurl', function () {
	return home_url( '/' );
} );
add_filter( 'login_headertext', function () {
	return get_bloginfo( 'name' );
} );

// Tras iniciar sesion, los clientes van a "Mi cuenta" (los administradores, al escritorio).
add_filter( 'login_redirect', function ( $redirect_to, $requested, $user ) {
	if ( $user instanceof WP_User && ! user_can( $user, 'edit_posts' ) && function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'myaccount' );
	}
	return $redirect_to;
}, 10, 3 );

/**
 * A partir de aqui: hooks, custom post types, integracion WooCommerce,
 * shortcodes y demas logica del ecommerce SPIRUP.
 */

/* ==========================================================================
   PANEL ADMINISTRATIVO DE TEXTOS
   Apariencia -> Personalizar -> "SPIR UP · Textos"
   Permite editar los textos reales de la portada (con vista previa en vivo).
   Los textos que van dentro de imagenes (hero, palabras CITRUS/REBEL, banda
   de features, "Ingredientes con proposito", "Del cultivo") NO son editables
   como texto porque estan horneados en la imagen del diseño.
   ========================================================================== */

/**
 * Devuelve un texto editable por su clave (o su valor por defecto).
 */
function spirup_txt( $key, $default = null ) {
	if ( null === $default ) {
		$fields  = spirup_text_fields();
		$default = isset( $fields[ $key ] ) ? $fields[ $key ][2] : '';
	}
	return get_theme_mod( 'spirup_txt_' . $key, $default );
}

/**
 * Primera letra en mayuscula (con acentos), p. ej. "energía limpia" => "Energía limpia".
 * Asi el texto sale bien aunque en el personalizador se haya guardado en minuscula.
 */
function spirup_ucfirst( $s ) {
	$s = trim( (string) $s );
	return '' === $s ? '' : mb_strtoupper( mb_substr( $s, 0, 1 ) ) . mb_substr( $s, 1 );
}

/**
 * Definicion de los campos editables.
 * clave => array( seccion, etiqueta, valor por defecto, 'text'|'textarea' )
 */
function spirup_text_fields() {
	return array(
		// Portada / general
		'hero_cta'        => array( 'general', 'Botón de la portada', 'Pruébala ahora', 'text' ),
		'elige_title'     => array( 'general', 'Título "Elige cómo quieres"', 'Elige cómo quieres tu SPIR UP', 'text' ),
		// Slider Citrus
		'sl_citrus_name'  => array( 'slider', 'Citrus · Nombre', 'Citrus Blue', 'text' ),
		'sl_citrus_tag'   => array( 'slider', 'Citrus · Subtítulo (naranja)', 'REFRESCANTE, ENRIQUECIDA Y NATURAL:', 'text' ),
		'sl_citrus_desc'  => array( 'slider', 'Citrus · Descripción', 'Energía limpia para potenciar tu día.', 'textarea' ),
		'sl_citrus_pills' => array( 'slider', 'Citrus · Pastillas (separadas por coma)', 'Fresco, Refrescante, Ligero, Energía natural', 'text' ),
		// Slider Rebel
		'sl_rebel_name'   => array( 'slider', 'Rebel · Nombre', 'Rebel Blue', 'text' ),
		'sl_rebel_tag'    => array( 'slider', 'Rebel · Subtítulo (naranja)', 'REFRESCANTE, ENRIQUECIDA Y NATURAL:', 'text' ),
		'sl_rebel_desc'   => array( 'slider', 'Rebel · Descripción', 'Energía limpia para potenciar tu día.', 'textarea' ),
		'sl_rebel_pills'  => array( 'slider', 'Rebel · Pastillas (separadas por coma)', 'Intenso, Refrescante, Moderno, Energía natural', 'text' ),
		// Franja CTA
		'cta_title'       => array( 'cta', 'Título', '¿Listo para probarlo?', 'text' ),
		'cta_btn1'        => array( 'cta', 'Botón 1', 'Pedir ahora', 'text' ),
		'cta_btn2'        => array( 'cta', 'Botón 2', 'Más detalles', 'text' ),
		// Microalgas
		'micro_title'     => array( 'micro', 'Título', 'El potencial de las microalgas, en una bebida que sí disfrutarás', 'textarea' ),
		'micro_item1'     => array( 'micro', 'Ítem 1', 'No es una gaseosa común', 'text' ),
		'micro_item2'     => array( 'micro', 'Ítem 2', 'No es una bebida energizante', 'text' ),
		'micro_item3'     => array( 'micro', 'Ítem 3 (con check)', 'Es una nueva forma de nutrirte y disfrutar', 'text' ),
		'micro_claim1'    => array( 'micro', 'Frase final (línea 1)', 'SPIR UP no compite contra otras gaseosas,', 'text' ),
		'micro_claim2'    => array( 'micro', 'Frase final (línea 2, negrita)', 'SPIR UP crea una nueva categoría', 'text' ),
		// Reserva
		'res_title'       => array( 'reserva', 'Título', 'Únete al lanzamiento exclusivo de SPIR UP', 'textarea' ),
		/* Un salto de linea en el texto = salto fijo en escritorio (mismos cortes que el figma) */
		'res_lead1'       => array( 'reserva', 'Párrafo 1', "La primera producción de Spir Up estará disponible para un grupo selecto de personas\nantes de su lanzamiento oficial.", 'textarea' ),
		'res_lead2'       => array( 'reserva', 'Párrafo 2', "Déjanos tu correo y recibe acceso prioritario, novedades exclusivas y la oportunidad de\nconseguir las primeras unidades.", 'textarea' ),
		'res_note'        => array( 'reserva', 'Nota', 'Sin spam. Solo te escribimos cuando llegue tu turno.', 'text' ),
	);
}

/**
 * Registra el panel, las secciones y los controles en el Customizer.
 */
function spirup_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'spirup_textos', array(
		'title'       => 'SPIR UP · Textos',
		'description' => 'Edita los textos de la portada. Los textos dentro de imágenes del diseño no aparecen aquí.',
		'priority'    => 22,
	) );

	$sections = array(
		'general' => 'Portada / General',
		'slider'  => 'Slider de sabores',
		'cta'     => 'Franja "¿Listo para probarlo?"',
		'micro'   => 'El potencial de las microalgas',
		'reserva' => 'Reserva tu lugar',
	);
	foreach ( $sections as $sid => $stitle ) {
		$wp_customize->add_section( 'spirup_sec_' . $sid, array(
			'title' => $stitle,
			'panel' => 'spirup_textos',
		) );
	}

	foreach ( spirup_text_fields() as $key => $f ) {
		$is_ta = ( isset( $f[3] ) && 'textarea' === $f[3] );
		$wp_customize->add_setting( 'spirup_txt_' . $key, array(
			'default'           => $f[2],
			'sanitize_callback' => $is_ta ? 'sanitize_textarea_field' : 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'spirup_txt_' . $key, array(
			'label'   => $f[1],
			'section' => 'spirup_sec_' . $f[0],
			'type'    => $is_ta ? 'textarea' : 'text',
		) );
	}
}
add_action( 'customize_register', 'spirup_customize_register' );

/* ==========================================================================
   Checkout: PRIMERO se llena el formulario como invitado y, al pulsar
   "Realizar el pedido", recien se pide entrar o crear cuenta (ventana
   emergente). Tras entrar, el pedido se envia solo con los datos ya escritos.
   - Servidor: si el formulario esta completo pero no hay sesion => error
     especial "spirup_login_required" (el JS lo convierte en la ventana).
   - AJAX spirup_auth_login / spirup_auth_register: inician sesion o crean
     la cuenta y devuelven un nonce nuevo de checkout para reenviar el pedido.
   ========================================================================== */

// 1) Exigir sesion SOLO cuando el resto del formulario ya esta bien.
add_action( 'woocommerce_after_checkout_validation', function ( $data, $errors ) {
	if ( is_user_logged_in() || $errors->has_errors() ) {
		return;
	}
	$errors->add(
		'spirup_login_required',
		'<span data-spirup-auth="1">Para terminar tu pedido, inicia sesión o crea tu cuenta.</span>'
	);
}, 10, 2 );

/**
 * Respuesta comun tras entrar/crear cuenta.
 *
 * El nonce nuevo del checkout NO se puede generar aqui: va ligado al token de
 * sesion, que el navegador recibe recien con la respuesta de este request. El
 * JS lo pide en una segunda llamada (spirup_auth_nonce), ya con la cookie.
 */
function spirup_auth_success( $user_id, $guest_cart = null ) {
	wp_set_current_user( $user_id );

	if ( function_exists( 'WC' ) && WC()->session ) {
		// La sesion pasa a estar a nombre del cliente: sin esto el carrito del
		// invitado se queda en la sesion vieja y el checkout se ve vacio.
		WC()->session->init_session_cookie();
		if ( ! empty( $guest_cart ) ) {
			WC()->session->set( 'cart', $guest_cart );
			WC()->cart->get_cart_from_session();
			WC()->cart->calculate_totals();
			WC()->cart->set_session();
		}
	}
	wp_send_json_success( array( 'ok' => true ) );
}

/**
 * Carrito del invitado tal cual esta guardado en la sesion (para restaurarlo
 * despues de iniciar sesion).
 */
function spirup_auth_guest_cart() {
	return ( function_exists( 'WC' ) && WC()->session ) ? WC()->session->get( 'cart' ) : null;
}

// 2) Nonce fresco del checkout, ya con la sesion iniciada en el navegador.
//    NO usa el nonce "spirup_auth": ese se genero como invitado y, al cambiar
//    el usuario, deja de ser valido. Basta con exigir sesion y mismo origen:
//    no cambia nada y la respuesta no se puede leer desde otro dominio.
add_action( 'wp_ajax_spirup_auth_nonce', function () {
	$ref  = isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
	$home = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( ! $ref || wp_parse_url( $ref, PHP_URL_HOST ) !== $home ) {
		wp_send_json_error( array( 'message' => 'Origen no válido.' ), 403 );
	}
	wp_send_json_success( array(
		'nonce' => wp_create_nonce( 'woocommerce-process_checkout' ),
	) );
} );

// 3a) Entrar.
function spirup_auth_login() {
	check_ajax_referer( 'spirup_auth', 'nonce' );
	$login = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
	$pass  = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '';   // phpcs:ignore
	if ( '' === $login || '' === $pass ) {
		wp_send_json_error( array( 'message' => 'Escribe tu correo y tu contraseña.' ) );
	}
	$guest_cart = spirup_auth_guest_cart();
	$user = wp_signon( array(
		'user_login'    => $login,
		'user_password' => $pass,
		'remember'      => true,
	), is_ssl() );
	if ( is_wp_error( $user ) ) {
		wp_send_json_error( array( 'message' => 'Correo o contraseña incorrectos.' ) );
	}
	spirup_auth_success( $user->ID, $guest_cart );
}
add_action( 'wp_ajax_nopriv_spirup_auth_login', 'spirup_auth_login' );
add_action( 'wp_ajax_spirup_auth_login', 'spirup_auth_login' );

// 3b) Crear cuenta: nombre completo, correo, celular, contrasena y terminos.
function spirup_auth_register() {
	check_ajax_referer( 'spirup_auth', 'nonce' );
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$pass  = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '';   // phpcs:ignore
	$pass2 = isset( $_POST['password2'] ) ? (string) wp_unslash( $_POST['password2'] ) : '';  // phpcs:ignore
	$name  = isset( $_POST['full_name'] ) ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) : '';
	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$terms = ! empty( $_POST['terms'] );

	// El nombre completo se parte en nombre + apellidos (lo que pide WooCommerce).
	$name  = trim( preg_replace( '/\s+/', ' ', $name ) );
	$parts = $name ? explode( ' ', $name ) : array();
	$first = array_shift( $parts );
	$last  = implode( ' ', $parts );

	if ( '' === $name ) {
		wp_send_json_error( array( 'message' => 'Escribe tu nombre completo.', 'field' => 'full_name' ) );
	}
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Escribe un correo válido.', 'field' => 'email' ) );
	}
	if ( email_exists( $email ) ) {
		wp_send_json_error( array( 'message' => 'Ese correo ya tiene cuenta. Entra con tu contraseña.', 'switch' => 'login' ) );
	}
	if ( strlen( preg_replace( '/\D/', '', $phone ) ) < 6 ) {
		wp_send_json_error( array( 'message' => 'Escribe tu número de celular.', 'field' => 'phone' ) );
	}
	if ( strlen( $pass ) < 6 ) {
		wp_send_json_error( array( 'message' => 'La contraseña debe tener al menos 6 caracteres.', 'field' => 'password' ) );
	}
	if ( $pass !== $pass2 ) {
		wp_send_json_error( array( 'message' => 'Las contraseñas no coinciden.', 'field' => 'password2' ) );
	}
	if ( ! $terms ) {
		wp_send_json_error( array( 'message' => 'Debes aceptar los términos y condiciones.', 'field' => 'terms' ) );
	}

	$guest_cart = spirup_auth_guest_cart();
	$user_id = wc_create_new_customer( $email, wc_create_new_customer_username( $email ), $pass, array(
		'first_name' => $first,
		'last_name'  => $last,
	) );
	if ( is_wp_error( $user_id ) ) {
		wp_send_json_error( array( 'message' => wp_strip_all_tags( $user_id->get_error_message() ) ) );
	}
	// Datos que ya nos dio: quedan guardados en su cuenta y salen prellenados
	// la proxima vez que compre.
	update_user_meta( $user_id, 'billing_first_name', $first );
	update_user_meta( $user_id, 'billing_last_name', $last );
	update_user_meta( $user_id, 'billing_email', $email );
	update_user_meta( $user_id, 'billing_phone', $phone );

	wc_set_customer_auth_cookie( $user_id );
	spirup_auth_success( $user_id, $guest_cart );
}
add_action( 'wp_ajax_nopriv_spirup_auth_register', 'spirup_auth_register' );
add_action( 'wp_ajax_spirup_auth_register', 'spirup_auth_register' );

// 4) Datos para el JS + la ventana (solo en el checkout y sin sesion).
add_action( 'wp_enqueue_scripts', function () {
	if ( is_user_logged_in() || ! function_exists( 'is_checkout' ) ) {
		return;
	}
	$en_checkout = is_checkout() && ! is_wc_endpoint_url();
	if ( ! $en_checkout && ! is_account_page() ) {
		return;
	}
	wp_localize_script( 'spirup-js', 'SPIRUP_AUTH', array(
		'ajax'  => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'spirup_auth' ),
	) );
}, 20 );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_wc_endpoint_url() || is_user_logged_in() ) {
		return;
	}
	$img = get_stylesheet_directory_uri() . '/imagenes';
	?>
	<div class="spirup-authmodal" id="spirup-authmodal" hidden>
		<div class="spirup-authmodal__bg" data-spirup-auth-close></div>
		<div class="spirup-auth__card spirup-authmodal__card" role="dialog" aria-modal="true" aria-labelledby="spirup-authmodal-title">
			<button type="button" class="spirup-authmodal__close" data-spirup-auth-close aria-label="Cerrar">&times;</button>
			<img class="spirup-authmodal__logo" src="<?php echo esc_url( $img . '/logo-spirup.png' ); ?>" srcset="<?php echo esc_url( $img . '/logo-spirup.png' ); ?> 1x, <?php echo esc_url( $img . '/logo-spirup@2x.png' ); ?> 2x" width="154" height="186" alt="Spir Up">
			<h2 class="spirup-auth__title" id="spirup-authmodal-title">Ya casi está</h2>
			<p class="spirup-auth__sub">Entra o crea tu cuenta para confirmar el pedido. Tus datos ya quedaron guardados.</p>

			<div class="spirup-auth__tabs" role="tablist">
				<button type="button" class="spirup-auth__tab is-active" data-spirup-tab="login" role="tab">Iniciar sesión</button>
				<button type="button" class="spirup-auth__tab" data-spirup-tab="register" role="tab">Crear cuenta</button>
			</div>

			<p class="spirup-auth__error" data-spirup-auth-error hidden></p>

			<form class="spirup-auth__panel is-active" data-spirup-panel="login" data-spirup-auth-form="login" novalidate>
				<p class="form-row">
					<label for="sa-username">Correo electrónico o usuario</label>
					<input type="text" id="sa-username" name="username" autocomplete="username" placeholder="tucorreo@ejemplo.com">
				</p>
				<p class="form-row">
					<label for="sa-password">Contraseña</label>
					<input type="password" id="sa-password" name="password" autocomplete="current-password" placeholder="••••••••">
				</p>
				<div class="spirup-auth__row">
					<span></span>
					<a class="spirup-auth__lost" href="<?php echo esc_url( wp_lostpassword_url() ); ?>" target="_blank" rel="noopener">¿Olvidaste tu contraseña?</a>
				</div>
				<button type="submit" class="spirup-auth__btn">Entrar y confirmar pedido</button>
			</form>

			<form class="spirup-auth__panel" data-spirup-panel="register" data-spirup-auth-form="register" novalidate>
				<?php spirup_register_fields( 'sa' ); ?>
				<button type="submit" class="spirup-auth__btn">Registrarme y confirmar pedido</button>
				<p class="spirup-auth__swap">¿Ya tienes una cuenta? <button type="button" data-spirup-tab="login">Inicia sesión</button></p>
			</form>
		</div>
	</div>
	<?php
} );

/**
 * Campos del formulario "Crear cuenta" (figma): nombre completo, correo,
 * celular, contrasena + confirmacion y aceptacion de terminos.
 *
 * Se usa igual en la pagina "Mi cuenta" y en la ventana del checkout, para que
 * los dos formularios sean identicos.
 *
 * @param string $p Prefijo de los id (evita ids repetidos si salen los dos).
 */
function spirup_register_fields( $p = 'reg' ) {
	$priv  = get_page_by_path( 'politica-de-privacidad' );
	$terms = get_page_by_path( 'terminos-y-condiciones' );
	?>
	<p class="form-row">
		<label for="<?php echo esc_attr( $p ); ?>_name">Nombre completo</label>
		<input type="text" name="full_name" id="<?php echo esc_attr( $p ); ?>_name" autocomplete="name" placeholder="Ingresa tu nombre y apellido">
	</p>
	<p class="form-row">
		<label for="<?php echo esc_attr( $p ); ?>_email">Correo electrónico</label>
		<input type="email" name="email" id="<?php echo esc_attr( $p ); ?>_email" autocomplete="email" placeholder="ejemplo@correo.com">
	</p>
	<p class="form-row">
		<label for="<?php echo esc_attr( $p ); ?>_phone">Número de celular</label>
		<input type="tel" name="phone" id="<?php echo esc_attr( $p ); ?>_phone" autocomplete="tel" placeholder="+51 987 654 321">
	</p>
	<p class="form-row">
		<label for="<?php echo esc_attr( $p ); ?>_pass1">Contraseña</label>
		<input type="password" name="password" id="<?php echo esc_attr( $p ); ?>_pass1" autocomplete="new-password" placeholder="Mínimo 6 caracteres">
	</p>
	<p class="form-row">
		<label for="<?php echo esc_attr( $p ); ?>_pass2">Confirmar contraseña</label>
		<input type="password" name="password2" id="<?php echo esc_attr( $p ); ?>_pass2" autocomplete="new-password" placeholder="Repite tu contraseña">
	</p>
	<label class="spirup-auth__terms">
		<input type="checkbox" name="terms" value="1">
		<span>Acepto los
			<a href="<?php echo esc_url( $terms ? get_permalink( $terms ) : '#' ); ?>" target="_blank" rel="noopener">Términos y condiciones</a>
			y la
			<a href="<?php echo esc_url( $priv ? get_permalink( $priv ) : '#' ); ?>" target="_blank" rel="noopener">política de Privacidad</a>
		</span>
	</label>
	<?php
}

/* ==========================================================================
   Tienda SIEMPRE visible: apaga el modo "Proximamente" de WooCommerce.

   Con "Proximamente" + "Restringir solo a las paginas de la tienda", el inicio
   se ve normal pero el CARRITO, el PAGO y MI CUENTA muestran la pantalla
   "Tenemos grandes proyectos por anunciar". Por eso el aviso aparecia justo al
   ir a pagar. Se apaga aqui para que no dependa de un ajuste del servidor.

   Si algun dia quieres volver a cerrar la tienda, borra este bloque y usa
   WooCommerce > Ajustes > Visibilidad del sitio.
   ========================================================================== */
// Gancho oficial de WooCommerce (desde la 9.1): excluye la peticion de la
// pantalla "Proximamente". No toca el ajuste guardado, asi que el panel de
// WooCommerce > Ajustes > Visibilidad del sitio se sigue pudiendo usar.
add_filter( 'woocommerce_coming_soon_exclude', '__return_true' );

// Checkout sin la barra de cupon (el figma no la lleva).
add_action( 'wp', function () {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
} );

/* ==========================================================================
   Diagnostico de metodos de pago (SOLO lo ve el administrador).

   Sirve para saber por que el checkout dice "no hay metodos de pago
   disponibles": lista las pasarelas instaladas, si estan activadas y si
   WooCommerce las considera disponibles, junto con moneda, pais y HTTPS.
   Cuando Culqi ya funcione, se puede borrar este bloque.
   ========================================================================== */
add_action( 'woocommerce_review_order_before_payment', function () {
	if ( ! current_user_can( 'manage_woocommerce' ) || ! function_exists( 'WC' ) ) {
		return;
	}
	$todas  = WC()->payment_gateways()->payment_gateways();
	$dispo  = WC()->payment_gateways()->get_available_payment_gateways();
	$activo = get_option( 'woocommerce_default_country' );
	?>
	<div class="spirup-diag">
		<strong>Diagnóstico de pagos (solo lo ves tú como administrador)</strong>
		<p>
			Moneda: <b><?php echo esc_html( get_woocommerce_currency() ); ?></b> ·
			País de la tienda: <b><?php echo esc_html( $activo ); ?></b> ·
			HTTPS: <b><?php echo is_ssl() ? 'sí' : 'NO'; ?></b> ·
			Total: <b><?php echo wp_kses_post( wc_price( WC()->cart ? WC()->cart->get_total( 'edit' ) : 0 ) ); ?></b>
		</p>
		<?php if ( ! $todas ) : ?>
			<p>No hay <b>ninguna</b> pasarela instalada. Falta instalar y activar el plugin de Culqi.</p>
		<?php else : ?>
			<ul>
				<?php foreach ( $todas as $id => $g ) :
					$encendida = ( 'yes' === $g->enabled );
					$usable    = isset( $dispo[ $id ] );
					?>
					<li>
						<b><?php echo esc_html( $g->get_method_title() ? $g->get_method_title() : $id ); ?></b>
						<code><?php echo esc_html( $id ); ?></code> —
						activada: <b><?php echo $encendida ? 'sí' : 'NO'; ?></b>,
						disponible en este pedido: <b><?php echo $usable ? 'sí' : 'NO'; ?></b>
						<?php if ( $encendida && ! $usable ) : ?>
							<em>(está activada pero se oculta: suele ser por la moneda, el país o que falta HTTPS)</em>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
} );
