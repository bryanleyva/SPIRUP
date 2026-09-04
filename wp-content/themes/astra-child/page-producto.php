<?php
/**
 * Plantilla de pagina: Producto (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "producto".
 *
 * Se arma en secciones con TEXTO real (no imagen aplanada, que se veia pixelada):
 *   1. Inicial  -> inicial_productos.png (fondo con foto+iconos+barra) + titulo,
 *                  etiquetas de beneficios y lata (Rebel) con zoom al pasar el mouse.
 *   2-5. Sabores/Ingredientes -> fondos con la marca de agua; encima el texto.
 *
 * Una sola LATA fija (sticky) en el centro que se transforma con el scroll:
 *   Citrus (frente) -> gira/espejo en Ingredientes -> Rebel (frente) -> gira/espejo.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_stylesheet_directory_uri() . '/imagenes';

get_header();
?>

<main class="spirup-main spirup-prodx">

	<?php /* ============ 1. INICIAL ============ */ ?>
	<section class="prodx-hero">
		<img class="prodx-hero__bg" src="<?php echo esc_url( $img . '/inicial_productos.png' ); ?>" alt="Spir Up" fetchpriority="high">

		<h1 class="prodx-hero__title">
			Refrescante por <strong>naturaleza.</strong><br>
			Respaldada por la <strong class="c-orange">ciencia.</strong>
		</h1>

		<?php /* Etiquetas de beneficios (los iconos y lineas ya vienen en el fondo) */ ?>
		<span class="prodx-lbl prodx-lbl--l1">Ficocianina pura<br>garantizada<br>por lata</span>
		<span class="prodx-lbl prodx-lbl--l2">Antinflamatorio</span>
		<span class="prodx-lbl prodx-lbl--l3">Antioxidantes</span>
		<span class="prodx-lbl prodx-lbl--r1">Sin azúcar<br>añadida</span>
		<span class="prodx-lbl prodx-lbl--r2">Equilibrio<br>celular</span>
		<span class="prodx-lbl prodx-lbl--r3">Apoyo<br>inmunológico</span>

		<?php /* Lata central (Rebel) con zoom al pasar el mouse */ ?>
		<img class="prodx-hero__can" src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="Lata Spir Up Rebel Blue">
	</section>

	<?php /* ============ 2-5. ESCENARIO con la lata fija que se transforma ============ */ ?>
	<div class="prodx-stage" id="prodxStage" data-flavor="citrus" data-face="front">

		<div class="prodx-stage__canwrap" aria-hidden="true">
			<div class="prodx-can" id="prodxCan">
				<img class="prodx-can__img is-citrus" src="<?php echo esc_url( $img . '/lata-spir-up 1 (2).png' ); ?>" alt="">
				<img class="prodx-can__img is-rebel"  src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="">
			</div>
		</div>

		<?php /* 2. CITRUS BLUE */ ?>
		<section class="prodx-sec s-flavor" data-sec="citrus-front">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/citrusblueproducto.png' ); ?>" alt="Citrus Blue">
			<div class="prodx-desc">
				<p class="prodx-desc__h">Bebida carbonatada sin&nbsp;azúcar con ficocianina</p>
				<p class="prodx-desc__p">un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</p>
			</div>
			<div class="prodx-explora">
				<p class="prodx-explora__h">Explora nuestros sabores</p>
				<p class="prodx-explora__opt">Rebel Blue</p>
				<p class="prodx-explora__opt is-on">Citrus Blue</p>
			</div>
		</section>

		<?php /* 3. INGREDIENTES (Citrus) */ ?>
		<section class="prodx-sec s-ing" data-sec="citrus-back">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/ingredientesproductos.png' ); ?>" alt="Ingredientes">
			<div class="prodx-ing prodx-ing--tl"><h3>Agua gasificada</h3><p>Frescura que se siente.</p></div>
			<div class="prodx-ing prodx-ing--bl"><h3>Ficocianina</h3><p>Antioxidantes de origen natural.</p></div>
			<div class="prodx-ing prodx-ing--tr is-blue"><h3>Extractos naturales</h3><p>El poder de la naturaleza en el limón y la hierba luisa.</p></div>
			<div class="prodx-ing prodx-ing--br is-orange"><h3>Vitamina C</h3><p>Soporte para tus defensas.</p></div>
		</section>

		<?php /* 4. REBEL BLUE */ ?>
		<section class="prodx-sec s-flavor" data-sec="rebel-front">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/rebelblueproductos.png' ); ?>" alt="Rebel Blue">
			<div class="prodx-desc">
				<p class="prodx-desc__h">Bebida carbonatada sin&nbsp;azúcar con ficocianina</p>
				<p class="prodx-desc__p">un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</p>
			</div>
			<div class="prodx-explora">
				<p class="prodx-explora__h">Explora nuestros sabores</p>
				<p class="prodx-explora__opt is-on">Rebel Blue</p>
				<p class="prodx-explora__opt">Citrus Blue</p>
			</div>
		</section>

		<?php /* 5. INGREDIENTES (Rebel) */ ?>
		<section class="prodx-sec s-ing" data-sec="rebel-back">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/ingredientesproductos.png' ); ?>" alt="Ingredientes">
			<div class="prodx-ing prodx-ing--tl"><h3>Agua gasificada</h3><p>Frescura que se siente.</p></div>
			<div class="prodx-ing prodx-ing--bl"><h3>Ficocianina</h3><p>Antioxidantes de origen natural.</p></div>
			<div class="prodx-ing prodx-ing--tr is-blue"><h3>Extractos naturales</h3><p>El poder de la naturaleza en el blueberry y el limón.</p></div>
			<div class="prodx-ing prodx-ing--br is-orange"><h3>Vitamina C</h3><p>Soporte para tus defensas.</p></div>
		</section>

	</div>
</main>

<script>
( function () {
	'use strict';
	var stage = document.getElementById( 'prodxStage' );
	if ( ! stage ) { return; }
	var secs = stage.querySelectorAll( '.prodx-sec[data-sec]' );
	if ( ! secs.length ) { return; }

	function apply( sec ) {
		var key = sec.getAttribute( 'data-sec' ) || 'citrus-front';
		var parts = key.split( '-' );          // ["citrus","front"] etc.
		stage.setAttribute( 'data-flavor', parts[0] );
		stage.setAttribute( 'data-face', parts[1] );
	}

	if ( ! ( 'IntersectionObserver' in window ) ) { return; }
	// La seccion "activa" es la que cruza el centro de la pantalla.
	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( en ) {
			if ( en.isIntersecting ) { apply( en.target ); }
		} );
	}, { root: null, rootMargin: '-45% 0px -45% 0px', threshold: 0 } );
	Array.prototype.forEach.call( secs, function ( s ) { io.observe( s ); } );
} )();
</script>

<?php
get_footer();
