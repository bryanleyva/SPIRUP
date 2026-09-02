<?php
/**
 * Plantilla de pagina: Producto (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "producto".
 * Usa la plantilla Group 76 (1).png de fondo y encima la lata (lata-spir-up 5.png)
 * centrada en el circulo, con zoom al pasar el mouse.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_stylesheet_directory_uri() . '/imagenes';

get_header();
?>

<main class="spirup-main">
	<section class="spirup-prod76">
		<div class="spirup-prod76__wrap">
			<img class="spirup-prod76__bg" src="<?php echo esc_url( $img . '/Group 76 (1).png' ); ?>" alt="Producto Spir Up">
			<img class="spirup-prod76__can" src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="Lata Spir Up Rebel Blue">
		</div>
	</section>
</main>

<?php
get_footer();
