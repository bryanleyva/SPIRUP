<?php
/**
 * Plantilla de pagina: Producto (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "producto".
 * Showcase de sabores (Citrus Blue / Rebel Blue) con selector, segun group 49 (1).png.
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

	<?php /* ===================== SHOWCASE de sabores (Citrus Blue / Rebel Blue) con selector ===================== */ ?>
	<section class="spirup-showcase" id="sabores" data-flavor="citrus">
		<span class="spirup-showcase__wm" data-wm>CITRUS BLUE</span>
		<div class="spirup-showcase__inner">
			<div class="spirup-showcase__side spirup-showcase__side--left">
				<div class="spirup-showcase__desc">
					<strong>Bebida carbonatada sin&nbsp;azúcar con ficocianina</strong>
					<p>un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias</p>
				</div>
				<div class="spirup-feat">
					<h3>Agua gasificada</h3>
					<p>Frescura que se siente</p>
				</div>
				<div class="spirup-feat">
					<h3>Ficocianina</h3>
					<p>Antioxidantes de origen&nbsp;natural</p>
				</div>
			</div>

			<div class="spirup-showcase__stage">
				<img class="spirup-showcase__can is-citrus" src="<?php echo esc_url( $img . '/lata-citrus.png' ); ?>" alt="Lata Spir Up Citrus Blue">
				<img class="spirup-showcase__can is-rebel" src="<?php echo esc_url( $img . '/lata-rebel.png' ); ?>" alt="Lata Spir Up Rebel Blue">
			</div>

			<div class="spirup-showcase__side spirup-showcase__side--right">
				<div class="spirup-showcase__explora">
					<p class="spirup-showcase__lbl">Explora nuestros sabores</p>
					<div class="spirup-showcase__tabs" role="tablist">
						<button type="button" class="spirup-showcase__tab" data-flavor-set="rebel" role="tab">Rebel Blue</button>
						<button type="button" class="spirup-showcase__tab is-active" data-flavor-set="citrus" role="tab" aria-selected="true">Citrus Blue</button>
					</div>
				</div>
				<div class="spirup-feat">
					<h3>Extractos naturales</h3>
					<p data-extractos>El poder de la naturaleza en el limón y la hierba luisa</p>
				</div>
				<div class="spirup-feat">
					<h3>Vitamina C</h3>
					<p>Soporte para tus defensas</p>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
