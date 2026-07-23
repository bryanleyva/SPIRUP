<?php
/**
 * Portada de SPIRUP (front-page).
 *
 * Enfoque por imagenes compuestas: cada bloque del diseno es una figura.
 * PARTE 1: grupo figuras 1 (hero + franja de valores).
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$img = get_stylesheet_directory_uri() . '/imagenes';
?>

<main class="spirup-main">

	<section class="spirup-figura">
		<img class="spirup-figura__img"
			src="<?php echo esc_url( $img . '/grupo figuras 1.png' ); ?>"
			alt="Refrescante por naturaleza. Funcional por ciencia.">
		<a class="spirup-figura__cta spirup-btn spirup-btn--orange" href="#reservar">Pruébala ahora ↗</a>
	</section>

	<?php /* ===================== PARTE 2 ===================== */ ?>
	<section class="spirup-parte2">
		<div class="spirup-parte2__inner">
			<?php /* Capa de FONDO (aqua + composicion completa). */ ?>
			<img class="spirup-figura__img"
				src="<?php echo esc_url( $img . '/parte2-bg.png' ); ?>"
				alt="Una lata con ciencia adentro. 355 ml de bebida gasificada formulada con un bioactivo reconocido por su potencial antioxidante.">

			<?php /* Lata 3D girando, DETRAS del agua (entre el fondo y la capa frontal). */ ?>
			<div id="spirup-lata3d" class="spirup-lata3d" aria-label="Lata Spir Up Citrus Blue en 3D"></div>

			<?php /* Capa FRONTAL: solo agua/limones/badges con fondo transparente,
			         para que el splash quede POR DELANTE de la lata. */ ?>
			<img class="spirup-parte2__front"
				src="<?php echo esc_url( $img . '/parte2-front.png' ); ?>"
				alt="" aria-hidden="true">
		</div>
	</section>

	<?php /* ===================== PARTE 3: Ingredientes ===================== */ ?>
	<section class="spirup-parte3">
		<?php /* Onda de conexion (transparente arriba = aqua; teal abajo). */ ?>
		<img class="spirup-parte3__wave"
			src="<?php echo esc_url( $img . '/parte3-wave.png' ); ?>" alt="" aria-hidden="true">

		<div class="spirup-parte3__panel">
			<div class="spirup-parte3__inner">
				<span class="spirup-parte3__badge">Que contiene</span>
				<h2 class="spirup-parte3__title">Ingredientes con propósito, nada de relleno</h2>

				<div class="spirup-parte3__cards">
					<div class="spirup-card">
						<div class="spirup-card__top">
							<img src="<?php echo esc_url( $img . '/ing-icon-1.png' ); ?>" alt="" aria-hidden="true">
						</div>
						<div class="spirup-card__body">
							<strong>Microalgas</strong>
							<span>Bioactivos funcionales de origen natural</span>
						</div>
					</div>
					<div class="spirup-card">
						<div class="spirup-card__top">
							<img src="<?php echo esc_url( $img . '/ing-icon-2.png' ); ?>" alt="" aria-hidden="true">
						</div>
						<div class="spirup-card__body">
							<strong>Agua gasificada</strong>
							<span>Contenido controlado de sodio</span>
						</div>
					</div>
					<div class="spirup-card">
						<div class="spirup-card__top">
							<img src="<?php echo esc_url( $img . '/ing-icon-3.png' ); ?>" alt="" aria-hidden="true">
						</div>
						<div class="spirup-card__body">
							<strong>Extractos naturales</strong>
							<span>Sin saborizantes artificiales</span>
						</div>
					</div>
					<div class="spirup-card">
						<div class="spirup-card__top">
							<img src="<?php echo esc_url( $img . '/ing-icon-4.png' ); ?>" alt="" aria-hidden="true">
						</div>
						<div class="spirup-card__body">
							<strong>Sin azúcar añadida</strong>
							<span>Dulzor equilibrado sin culpa</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php /* ===================== Franja CTA amarilla ===================== */ ?>
	<section class="spirup-cta">
		<div class="spirup-cta__inner">
			<h3 class="spirup-cta__title">¿Listo para probarlo?</h3>
			<div class="spirup-cta__actions">
				<a class="spirup-cta__btn spirup-cta__btn--solid" href="#pedir">Pedir ahora ↗</a>
				<a class="spirup-cta__btn spirup-cta__btn--ghost" href="#detalles">Más detalles</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
