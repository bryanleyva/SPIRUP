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

	<?php /* HERO = hero-inicio.png: la misma imagen Group 56 (foto + banda de valores +
		ola) pero con el titulo manuscrito horneado BORRADO (parchado con la foto sin
		texto). Encima va el titulo real en 4 lineas ("SPIR UP" en #C1CF3C) y el boton
		centrado debajo. Se sirve @2x via srcset. En movil: foto sola (Vector9-mob). */ ?>
	<section class="spirup-figura">
		<picture>
			<source media="(max-width: 820px)" srcset="<?php echo esc_url( $img . '/Vector 9.png' ); ?>">
			<img class="spirup-figura__img"
				src="<?php echo esc_url( $img . '/hero-inicio.png' ); ?>"
				srcset="<?php echo esc_url( $img . '/hero-inicio.png' ); ?> 1443w, <?php echo esc_url( $img . '/hero-inicio@2x.png' ); ?> 2886w"
				sizes="100vw"
				alt="Mano sosteniendo una lata de Spir Up bajo el sol. Respaldada por investigación, sin colorantes artificiales, propuesta sostenible">
		</picture>
		<div class="spirup-figura__overlay">
			<h2 class="spirup-figura__title">¡Un sorbo de <br class="br-m">vitalidad,<br> un sorbo de <br class="br-m"><span class="spirup-figura__brand">Spir&nbsp;Up!</span></h2>
			<a class="spirup-figura__cta spirup-btn spirup-btn--orange" href="#reservar"><?php echo esc_html( spirup_txt( 'hero_cta' ) ); ?></a>
		</div>
	</section>

	<?php
	/* ===================== PARTE 2 = SHOWCASE (carrusel de sabores) =====================
	   Cada sabor es un SLIDE construido con TEXTO REAL (ya NO una imagen/plantilla, que
	   pixelaba): palabra grande en contorno, copy, pills de color, y encima la lata + agua.
	   Al pulsar una flecha el slide entrante se desliza y cubre al anterior (js/spirup.js). */
	$sc_slides = array(
		'citrus' => array(
			'can'   => 'lata-spir-up 1 (2).png',
			'name'  => spirup_txt( 'sl_citrus_name' ),
			'word'  => 'CITRUS',
			'tag'   => spirup_txt( 'sl_citrus_tag' ),
			'desc'  => spirup_txt( 'sl_citrus_desc' ),
			'pills' => array_filter( array_map( 'trim', explode( ',', spirup_txt( 'sl_citrus_pills' ) ) ) ),
		),
		'rebel'  => array(
			'can'   => 'lata-spir-up 1 (3).png',
			'name'  => spirup_txt( 'sl_rebel_name' ),
			'word'  => 'REBEL',
			'tag'   => spirup_txt( 'sl_rebel_tag' ),
			'desc'  => spirup_txt( 'sl_rebel_desc' ),
			'pills' => array_filter( array_map( 'trim', explode( ',', spirup_txt( 'sl_rebel_pills' ) ) ) ),
		),
	);
	?>
	<section class="spirup-showcase" id="una-lata" data-flavor="citrus">
		<div class="spirup-showcase__viewport">
			<?php foreach ( $sc_slides as $key => $s ) : ?>
				<div class="spirup-showcase__slide is-<?php echo esc_attr( $key ); ?>" data-flavor-slide="<?php echo esc_attr( $key ); ?>" data-splash-video>
					<img class="spirup-showcase__wm" src="<?php echo esc_url( $img . '/marca-agua.png' ); ?>" alt="" aria-hidden="true">
					<span class="spirup-showcase__word" aria-hidden="true"><?php echo esc_html( $s['word'] ); ?></span>
					<div class="spirup-showcase__copy">
						<p class="spirup-showcase__flavor"><?php echo esc_html( $s['name'] ); ?></p>
						<p class="spirup-showcase__tag"><?php echo esc_html( $s['tag'] ); ?></p>
						<p class="spirup-showcase__desc"><?php echo esc_html( $s['desc'] ); ?></p>
					</div>
					<ul class="spirup-showcase__pills">
						<?php foreach ( $s['pills'] as $pi => $pill ) : ?>
							<li class="pill pill--<?php echo (int) ( $pi + 1 ); ?>"><?php echo esc_html( $pill ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="spirup-showcase__canbox">
						<video class="spirup-showcase__video" muted playsinline preload="auto" aria-hidden="true">
							<source src="<?php echo esc_url( $img . '/agua_realzada.mp4' ); ?>" type="video/mp4">
						</video>
						<img class="spirup-showcase__can" src="<?php echo esc_url( $img . '/' . $s['can'] ); ?>" alt="Lata Spir Up <?php echo esc_attr( $s['name'] ); ?>">
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php /* Flechas a los bordes (hijas de la seccion). Chevron en SVG => centrado perfecto. */ ?>
		<button type="button" class="spirup-showcase__arrow spirup-showcase__arrow--prev" data-flavor-prev aria-label="Sabor anterior">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5 8 12l7 7"/></svg>
		</button>
		<button type="button" class="spirup-showcase__arrow spirup-showcase__arrow--next" data-flavor-next aria-label="Siguiente sabor">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
		</button>
	</section>

	<?php /* ===================== Franja CTA amarilla ===================== */ ?>
	<section class="spirup-cta">
		<div class="spirup-cta__inner">
			<h3 class="spirup-cta__title"><?php echo esc_html( spirup_txt( 'cta_title' ) ); ?></h3>
			<div class="spirup-cta__actions">
				<a class="spirup-cta__btn spirup-cta__btn--solid" href="#productos"><?php echo esc_html( spirup_txt( 'cta_btn1' ) ); ?></a>
				<a class="spirup-cta__btn spirup-cta__btn--ghost" href="#por-que"><?php echo esc_html( spirup_txt( 'cta_btn2' ) ); ?></a>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 4 + INGREDIENTES + DEL CULTIVO: HTML real en TODOS los tamanos
	   (antes en desktop era una sola imagen horneada -Group75-top- que se veia pixelada). ===================== */ ?>
	<div class="spirup-mobileflow">
	<?php /* ===================== PARTE 4: Potencial de las microalgas ===================== */ ?>
	<section class="spirup-parte4">
		<div class="spirup-parte4__inner">
			<div class="spirup-parte4__media">
				<img src="<?php echo esc_url( $img . '/sesion4-beach.png' ); ?>"
					srcset="<?php echo esc_url( $img . '/sesion4-beach.png' ); ?> 690w, <?php echo esc_url( $img . '/sesion4-beach@2x.webp' ); ?> 1380w"
					sizes="(max-width: 860px) 100vw, 50vw"
					alt="Lata Spir Up Citrus Blue con gafas de sol junto a una piscina">
			</div>
			<div class="spirup-parte4__text">
				<h2 class="spirup-parte4__title"><?php echo esc_html( spirup_txt( 'micro_title' ) ); ?></h2>
				<ul class="spirup-parte4__list">
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span><?php echo esc_html( spirup_txt( 'micro_item1' ) ); ?></li>
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span><?php echo esc_html( spirup_txt( 'micro_item2' ) ); ?></li>
					<li class="is-yes"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9 17.5 20 6"/></svg></span><?php echo esc_html( spirup_txt( 'micro_item3' ) ); ?></li>
				</ul>
				<p class="spirup-parte4__claim"><?php echo esc_html( spirup_txt( 'micro_claim1' ) ); ?><br><strong><?php echo esc_html( spirup_txt( 'micro_claim2' ) ); ?></strong></p>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 3: Ingredientes (va despues de parte4) ===================== */ ?>
	<section class="spirup-parte3" id="beneficios">
		<?php /* Fondo: ola superior + degradado (SVG, nitido a cualquier tamano) y el swoosh
			(capa translucida recortada del diseno). Todo el texto es real. */ ?>
		<div class="spirup-parte3__bg" aria-hidden="true">
			<img class="spirup-parte3__wave" src="<?php echo esc_url( $img . '/parte3-fondo.svg' ); ?>" alt="">
			<img class="spirup-parte3__swoosh" src="<?php echo esc_url( $img . '/parte3-swoosh.png' ); ?>" alt="">
			<img class="spirup-parte3__swoosh-m" src="<?php echo esc_url( $img . '/parte3-swoosh-m.png' ); ?>" alt="">
		</div>
		<div class="spirup-parte3__inner">
			<h2 class="spirup-parte3__title">Ingredientes con<br class="br-m"> propósito<span class="p3-desk">,<br>nada de relleno</span></h2>
			<div class="spirup-parte3__grid">
				<?php
				$p3_cards = array(
					array( 'ing-microalgas.png', 'Microalgas', 'Bioactivos funcionales de origen natural' ),
					array( 'ing-agua.png', 'Agua gasificada', 'Contenido controlado de sodio' ),
					array( 'ing-extractos.png', 'Extractos naturales', 'Sin saborizantes artificiales' ),
					array( 'ing-sinazucar.png', 'Sin azúcar añadida', 'Dulzor equilibrado sin culpa' ),
				);
				foreach ( $p3_cards as $c ) : ?>
				<div class="spirup-p3card">
					<div class="spirup-p3card__top"><img class="spirup-p3card__ico" src="<?php echo esc_url( $img . '/' . $c[0] ); ?>" alt="" aria-hidden="true"></div>
					<div class="spirup-p3card__body">
						<strong><?php echo esc_html( $c[1] ); ?></strong>
						<span><?php echo esc_html( $c[2] ); ?></span>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== Del cultivo a la Lata (despues de Ingredientes) ===================== */ ?>
	<section class="spirup-cultivo" id="conocenos">
		<div class="spirup-cultivo__inner">
			<div class="spirup-cultivo__body">
				<h2 class="spirup-cultivo__title">Del cultivo a la Lata</h2>
				<p class="spirup-cultivo__sub">Cada etapa agrega valor</p>
				<?php /* Diagrama (circulos + flechas + labels El origen/desarrollo/resultado). Labels en los tercios. */ ?>
				<?php /* MOVIL: timeline VERTICAL (columna), como el figma. Desktop usa la imagen de abajo. */ ?>
				<ul class="spirup-cultivo__timeline">
					<li class="ct-step">
						<span class="ct-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 21c-2.2-4 1.6-6.5-.2-11"/><path d="M12 21c-1-6 2.4-8.5.2-14"/><path d="M17 21c2-4-1.4-6.6.4-11"/></svg></span>
						<div class="ct-txt"><h3>El origen</h3><p>Exploramos el potencial de las microalgas y sus compuestos bioactivos.</p></div>
					</li>
					<li class="ct-step">
						<span class="ct-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6"/><path d="M10 3v6l-4.6 8.6A1.8 1.8 0 0 0 7 21h10a1.8 1.8 0 0 0 1.6-2.4L14 9V3"/><path d="M8.4 14.5h7.2"/></svg></span>
						<div class="ct-txt"><h3>El desarrollo</h3><p>Trabajamos en la formulación para equilibrar funcionalidad, sabor y una experiencia refrescante.</p></div>
					</li>
					<li class="ct-step">
						<span class="ct-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="4" width="10" height="17" rx="3"/><path d="M9 4c0-1 .8-1.6 1.6-1.6h2.8c.8 0 1.6.6 1.6 1.6"/><path d="M8 9h8"/></svg></span>
						<div class="ct-txt"><h3>El resultado</h3><p>Una propuesta peruana que acerca la ciencia a la vida cotidiana.</p></div>
					</li>
				</ul>
				<?php /* DESKTOP: diagrama en HTML (circulos + flechas + labels), sin imagen => nitido */ ?>
				<ol class="spirup-cultivo__flow">
					<li class="cs"><span class="cs__circle"><img src="<?php echo esc_url( $img . '/cultivo-ico-1.png' ); ?>" alt="" aria-hidden="true"></span><h3>El origen</h3><p>Exploramos el potencial de las microalgas y sus compuestos bioactivos.</p></li>
					<li class="cs"><span class="cs__circle"><img src="<?php echo esc_url( $img . '/cultivo-ico-2.png' ); ?>" alt="" aria-hidden="true"></span><h3>El desarrollo</h3><p>Trabajamos en la formulación para equilibrar funcionalidad, sabor y una experiencia refrescante.</p></li>
					<li class="cs"><span class="cs__circle"><img src="<?php echo esc_url( $img . '/cultivo-ico-3.png' ); ?>" alt="" aria-hidden="true"></span><h3>El resultado</h3><p>Una propuesta peruana que acerca la ciencia a la vida cotidiana.</p></li>
				</ol>
			</div>
		</div>
	</section>
	</div><?php /* /.spirup-mobileflow */ ?>

	<?php
	/* ===================== PARTE 8: Productos "Elige como quieres tu SPIR UP" ===================== */
	$cart_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.2 12.2a1.5 1.5 0 0 0 1.5 1.3h8.4a1.5 1.5 0 0 0 1.5-1.2L21.5 7H6"/></svg>';
	$can_svg  = '<svg viewBox="0 0 48 96" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"><rect x="10" y="6" width="28" height="84" rx="8"/><path d="M14 6c0-2 2-3 4-3h12c2 0 4 1 4 3"/><path d="M12 26h24"/></svg>';
	$sp8_products = function_exists( 'wc_get_products' )
		? wc_get_products( array( 'status' => 'publish', 'limit' => 4, 'orderby' => 'menu_order date', 'order' => 'ASC' ) )
		: array();
	?>
	<section class="spirup-parte8" id="productos">
		<div class="spirup-parte8__inner">
			<h2 class="spirup-parte8__title"><?php echo esc_html( spirup_txt( 'elige_title' ) ); ?></h2>
			<div class="spirup-parte8__grid">
				<?php foreach ( $sp8_products as $product ) :
					$pid  = $product->get_id();
					$meta = trim( wp_strip_all_tags( $product->get_short_description() ) );
					if ( '' === $meta ) {
						// Sin descripcion corta: deducir del nombre (packs) o "355 ml" por defecto.
						$nm = $product->get_name();
						if ( preg_match( '/x\s*12/i', $nm ) ) { $meta = '12 latas'; }
						elseif ( preg_match( '/x\s*6/i', $nm ) ) { $meta = '6 latas'; }
						else { $meta = '355 ml'; }
					}
					?>
					<article class="spirup-product">
						<div class="spirup-product__img">
							<?php if ( $product->get_image_id() ) : ?>
								<?php echo $product->get_image( 'large' ); // phpcs:ignore -- 'large' es sin recorte: se ve la lata completa ?>
							<?php else : ?>
								<span class="spirup-product__ph"><?php echo $can_svg; // phpcs:ignore ?></span>
							<?php endif; ?>
						</div>
						<div class="spirup-product__row">
							<div class="spirup-product__info">
								<h3><?php echo esc_html( ucwords( mb_strtolower( $product->get_name(), 'UTF-8' ) ) ); ?></h3>
								<?php if ( $meta ) : ?><span class="spirup-product__meta"><?php echo esc_html( $meta ); ?></span><?php endif; ?>
								<span class="spirup-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
							</div>
							<a href="?add-to-cart=<?php echo esc_attr( $pid ); ?>"
								data-product_id="<?php echo esc_attr( $pid ); ?>" data-quantity="1"
								class="spirup-product__cart add_to_cart_button ajax_add_to_cart" rel="nofollow"
								aria-label="Añadir <?php echo esc_attr( $product->get_name() ); ?> al carrito">
								<?php echo $cart_svg; // phpcs:ignore ?>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 9: Reserva tu lugar (registro de correo) ===================== */ ?>
	<section class="spirup-parte9" id="reservar">
		<div class="spirup-parte9__inner">
			<h2 class="spirup-parte9__title"><?php echo esc_html( spirup_txt( 'res_title' ) ); ?></h2>
			<p class="spirup-parte9__lead"><?php echo esc_html( spirup_txt( 'res_lead1' ) ); ?></p>
			<p class="spirup-parte9__lead"><?php echo esc_html( spirup_txt( 'res_lead2' ) ); ?></p>
			<form class="spirup-parte9__form" action="#" method="post" onsubmit="return false;">
				<input type="email" name="email" placeholder="Tu correo electrónico" aria-label="Tu correo electrónico" required>
				<button type="submit" aria-label="Reservar">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 15 12 9 18"/></svg>
				</button>
			</form>
			<p class="spirup-parte9__note"><?php echo esc_html( spirup_txt( 'res_note' ) ); ?></p>
		</div>
	</section>

</main>

<?php
get_footer();
