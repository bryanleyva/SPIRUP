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

	// Fuente Poppins (Google Fonts).
	wp_enqueue_style(
		'spirup-poppins',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
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
		array( 'astra-parent-style', 'spirup-poppins' ),
		file_exists( $child_css ) ? filemtime( $child_css ) : '1.0.0'
	);

	// JS del tema (menu movil, etc.).
	$child_js = get_stylesheet_directory() . '/js/spirup.js';
	wp_enqueue_script(
		'spirup-js',
		get_stylesheet_directory_uri() . '/js/spirup.js',
		array(),
		file_exists( $child_js ) ? filemtime( $child_js ) : '1.0.0',
		true
	);

	// Lata 3D (Three.js) - solo en la portada.
	if ( is_front_page() ) {
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
 * Anadir una clase identificadora al <body>.
 */
function spirup_body_class( $classes ) {
	$classes[] = 'astra-child';
	return $classes;
}
add_filter( 'body_class', 'spirup_body_class' );

/**
 * A partir de aqui: hooks, custom post types, integracion WooCommerce,
 * shortcodes y demas logica del ecommerce SPIRUP.
 */
