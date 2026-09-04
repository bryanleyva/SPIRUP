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
		<span class="prodx-lbl prodx-lbl--r1">Sin azúcar<br><small>añadida</small></span>
		<span class="prodx-lbl prodx-lbl--r2">Equilibrio<br>celular</span>
		<span class="prodx-lbl prodx-lbl--r3">Apoyo<br>inmunológico</span>

		<?php /* Lata central (Rebel) con zoom al pasar el mouse */ ?>
		<img class="prodx-hero__can" src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="Lata Spir Up Rebel Blue">
	</section>

	<?php
	/* ============ 2-5. ESCENARIO con la lata fija que se transforma ============ */
	/* Agua (video) que va DETRAS de la lata en movil (el efecto se reusa en cada seccion). */
	$prodx_water = '<video class="prodx-sec__video" muted playsinline loop preload="none" aria-hidden="true"><source src="' . esc_url( $img . '/agua_realzada.mp4' ) . '" type="video/mp4"></video>';
	?>
	<div class="prodx-stage" id="prodxStage" data-flavor="citrus" data-face="front">

		<div class="prodx-stage__canwrap" aria-hidden="true">
			<div class="prodx-can" id="prodxCan">
				<img class="prodx-can__img is-citrus" src="<?php echo esc_url( $img . '/lata-spir-up 1 (2).png' ); ?>" alt="">
				<img class="prodx-can__img is-rebel"  src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="">
			</div>
		</div>

		<?php /* 2. CITRUS BLUE */ ?>
		<section class="prodx-sec s-flavor" data-sec="citrus-front" id="prod-citrus">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/citrusblueproducto.png' ); ?>" alt="Citrus Blue">
			<div class="prodx-sec__canbox">
				<?php echo $prodx_water; // phpcs:ignore ?>
				<img class="prodx-sec__can" src="<?php echo esc_url( $img . '/lata-spir-up 1 (2).png' ); ?>" alt="" aria-hidden="true">
			</div>
			<div class="prodx-desc">
				<p class="prodx-desc__h">Bebida carbonatada sin&nbsp;azúcar con ficocianina</p>
				<p class="prodx-desc__p">un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</p>
			</div>
			<div class="prodx-explora">
				<p class="prodx-explora__h">Explora nuestros sabores</p>
				<a class="prodx-explora__opt" href="#prod-rebel" data-flavor-go="rebel">Rebel Blue</a>
				<a class="prodx-explora__opt is-on" href="#prod-citrus" data-flavor-go="citrus">Citrus Blue</a>
			</div>
		</section>

		<?php /* 3. INGREDIENTES (Citrus) */ ?>
		<?php
		$ing_citrus = array(
			array( 'Agua gasificada', 'Frescura que se siente.', '', 'tl' ),
			array( 'Ficocianina', 'Antioxidantes de origen natural.', '', 'bl' ),
			array( 'Extractos naturales', 'El poder de la naturaleza en el limón y la hierba luisa.', 'is-blue', 'tr' ),
			array( 'Vitamina C', 'Soporte para tus defensas.', 'is-orange', 'br' ),
		);
		?>
		<section class="prodx-sec s-ing" data-sec="citrus-back">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/ingredientesproductos.png' ); ?>" alt="Ingredientes">
			<div class="prodx-sec__canbox">
				<?php echo $prodx_water; // phpcs:ignore ?>
				<img class="prodx-sec__can" src="<?php echo esc_url( $img . '/lata-spir-up 1 (2).png' ); ?>" alt="" aria-hidden="true">
			</div>
			<?php /* Desktop: 4 bloques en las esquinas */ ?>
			<?php foreach ( $ing_citrus as $it ) : ?>
				<div class="prodx-ing prodx-ing--<?php echo esc_attr( $it[3] ); ?> <?php echo esc_attr( $it[2] ); ?>"><h3><?php echo esc_html( $it[0] ); ?></h3><p><?php echo esc_html( $it[1] ); ?></p></div>
			<?php endforeach; ?>
			<?php /* Movil: carrusel de ingredientes (uno a la vez con flechas) */ ?>
			<div class="prodx-ingm">
				<button type="button" class="prodx-ingm__nav prodx-ingm__prev" aria-label="Ingrediente anterior"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5 8 12l7 7"/></svg></button>
				<div class="prodx-ingm__view">
					<?php foreach ( $ing_citrus as $i => $it ) : ?>
						<div class="prodx-ingm__item <?php echo esc_attr( $it[2] ); ?><?php echo 0 === $i ? ' is-on' : ''; ?>"><h3><?php echo esc_html( $it[0] ); ?></h3><p><?php echo esc_html( $it[1] ); ?></p></div>
					<?php endforeach; ?>
				</div>
				<button type="button" class="prodx-ingm__nav prodx-ingm__next" aria-label="Siguiente ingrediente"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg></button>
			</div>
		</section>

		<?php /* 4. REBEL BLUE */ ?>
		<section class="prodx-sec s-flavor" data-sec="rebel-front" id="prod-rebel">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/rebelblueproductos.png' ); ?>" alt="Rebel Blue">
			<div class="prodx-sec__canbox">
				<?php echo $prodx_water; // phpcs:ignore ?>
				<img class="prodx-sec__can" src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="" aria-hidden="true">
			</div>
			<div class="prodx-desc">
				<p class="prodx-desc__h">Bebida carbonatada sin&nbsp;azúcar con ficocianina</p>
				<p class="prodx-desc__p">un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</p>
			</div>
			<div class="prodx-explora">
				<p class="prodx-explora__h">Explora nuestros sabores</p>
				<a class="prodx-explora__opt is-on" href="#prod-rebel" data-flavor-go="rebel">Rebel Blue</a>
				<a class="prodx-explora__opt" href="#prod-citrus" data-flavor-go="citrus">Citrus Blue</a>
			</div>
		</section>

		<?php /* 5. INGREDIENTES (Rebel) */ ?>
		<?php
		$ing_rebel = array(
			array( 'Agua gasificada', 'Frescura que se siente.', '', 'tl' ),
			array( 'Ficocianina', 'Antioxidantes de origen natural.', '', 'bl' ),
			array( 'Extractos naturales', 'El poder de la naturaleza en el blueberry y el limón.', 'is-blue', 'tr' ),
			array( 'Vitamina C', 'Soporte para tus defensas.', 'is-orange', 'br' ),
		);
		?>
		<section class="prodx-sec s-ing" data-sec="rebel-back">
			<img class="prodx-sec__bg" src="<?php echo esc_url( $img . '/ingredientesproductos.png' ); ?>" alt="Ingredientes">
			<div class="prodx-sec__canbox">
				<?php echo $prodx_water; // phpcs:ignore ?>
				<img class="prodx-sec__can" src="<?php echo esc_url( $img . '/lata-spir-up 5.png' ); ?>" alt="" aria-hidden="true">
			</div>
			<?php foreach ( $ing_rebel as $it ) : ?>
				<div class="prodx-ing prodx-ing--<?php echo esc_attr( $it[3] ); ?> <?php echo esc_attr( $it[2] ); ?>"><h3><?php echo esc_html( $it[0] ); ?></h3><p><?php echo esc_html( $it[1] ); ?></p></div>
			<?php endforeach; ?>
			<div class="prodx-ingm">
				<button type="button" class="prodx-ingm__nav prodx-ingm__prev" aria-label="Ingrediente anterior"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5 8 12l7 7"/></svg></button>
				<div class="prodx-ingm__view">
					<?php foreach ( $ing_rebel as $i => $it ) : ?>
						<div class="prodx-ingm__item <?php echo esc_attr( $it[2] ); ?><?php echo 0 === $i ? ' is-on' : ''; ?>"><h3><?php echo esc_html( $it[0] ); ?></h3><p><?php echo esc_html( $it[1] ); ?></p></div>
					<?php endforeach; ?>
				</div>
				<button type="button" class="prodx-ingm__nav prodx-ingm__next" aria-label="Siguiente ingrediente"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg></button>
			</div>
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

/* Ingredientes en MOVIL: carrusel (uno a la vez con flechas) */
( function () {
	'use strict';
	var cars = document.querySelectorAll( '.prodx-ingm' );
	Array.prototype.forEach.call( cars, function ( car ) {
		var items = car.querySelectorAll( '.prodx-ingm__item' );
		if ( ! items.length ) { return; }
		var idx = 0;
		function show( n ) {
			idx = ( n + items.length ) % items.length;
			for ( var i = 0; i < items.length; i++ ) { items[ i ].classList.toggle( 'is-on', i === idx ); }
		}
		var prev = car.querySelector( '.prodx-ingm__prev' );
		var next = car.querySelector( '.prodx-ingm__next' );
		if ( prev ) { prev.addEventListener( 'click', function () { show( idx - 1 ); } ); }
		if ( next ) { next.addEventListener( 'click', function () { show( idx + 1 ); } ); }
	} );
} )();

/* "Explora nuestros sabores": ir al sabor elegido (scroll suave) */
( function () {
	'use strict';
	var links = document.querySelectorAll( '.prodx-explora__opt[href^="#"]' );
	Array.prototype.forEach.call( links, function ( a ) {
		a.addEventListener( 'click', function ( e ) {
			var t = document.getElementById( a.getAttribute( 'href' ).slice( 1 ) );
			if ( ! t ) { return; }
			e.preventDefault();
			var y = t.getBoundingClientRect().top + window.scrollY - 70;
			window.scrollTo( { top: y, behavior: 'smooth' } );
		} );
	} );
} )();

/* Agua detras de la lata: reproducir el video al entrar en pantalla (movil) */
( function () {
	'use strict';
	var vids = document.querySelectorAll( '.prodx-sec__video' );
	if ( ! vids.length ) { return; }
	if ( ! ( 'IntersectionObserver' in window ) ) {
		Array.prototype.forEach.call( vids, function ( v ) { try { v.play(); } catch ( e ) {} } );
		return;
	}
	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( en ) {
			if ( en.isIntersecting ) { var p = en.target.play(); if ( p && p.catch ) { p.catch( function () {} ); } }
			else { try { en.target.pause(); } catch ( e ) {} }
		} );
	}, { threshold: 0.25 } );
	Array.prototype.forEach.call( vids, function ( v ) { io.observe( v ); } );
} )();
</script>

<?php
get_footer();
