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
			src="<?php echo esc_url( $img . '/hero-carrusel.png' ); ?>"
			alt="¡Un sorbo de vitalidad, un sorbo de Spir Up!">
		<a class="spirup-figura__cta spirup-btn spirup-btn--orange" href="#reservar">Pruébala ahora</a>

		<?php
		/* Carrusel que RECORRE la ola: el texto va sobre un path sinusoidal de UN
		   periodo (id=spirupwavepath, x 0..1443) igual a la ola de la imagen.
		   Se dibujan DOS copias identicas (la 2a corrida +1443) y el grupo entero
		   se traslada -1443 en loop => el texto fluye siguiendo la curva, sin costura. */
		$W = 1443; $cy = 102; $amp = 34; $d = 'M 0 ' . $cy;
		$arc = 0.0; $px = 0.0; $py = $cy;
		for ( $x = 3; $x <= $W; $x += 3 ) {
			$y = $cy + $amp * sin( 2 * M_PI * $x / $W );
			$arc += sqrt( ( $x - $px ) * ( $x - $px ) + ( $y - $py ) * ( $y - $py ) );
			$px = $x; $py = $y;
			if ( $x % 12 === 0 || $x >= $W - 3 ) { $d .= ' L ' . $x . ' ' . round( $y, 1 ); }
		}
		$arc = round( $arc ); // longitud de arco de un periodo (para que el texto lo llene exacto)
		$wave_bolt = '&#160;&#160;<tspan class="spirup-wavebolt" dy="-1">&#9889;&#65038;</tspan>&#160;&#160;';
		$wave_text = '&#160;&#160;Respaldada por investigación' . $wave_bolt . 'Sin colorantes artificiales' . $wave_bolt . 'Propuesta sostenible' . $wave_bolt;
		?>
		<div class="spirup-figura__wave" aria-hidden="true">
			<svg class="spirup-wavesvg" viewBox="0 0 1443 168" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">
				<defs><path id="spirupwavepath" d="<?php echo esc_attr( $d ); ?>" fill="none"></path></defs>
				<g class="spirup-wavescroll">
					<animateTransform attributeName="transform" type="translate" from="0 0" to="-1443 0" dur="16s" repeatCount="indefinite"></animateTransform>
					<text class="spirup-wavetext" textLength="<?php echo esc_attr( $arc ); ?>" lengthAdjust="spacingAndGlyphs">
						<textPath href="#spirupwavepath"><?php echo $wave_text; // phpcs:ignore ?></textPath>
					</text>
					<text class="spirup-wavetext" transform="translate(1443 0)" textLength="<?php echo esc_attr( $arc ); ?>" lengthAdjust="spacingAndGlyphs">
						<textPath href="#spirupwavepath"><?php echo $wave_text; // phpcs:ignore ?></textPath>
					</text>
				</g>
			</svg>
		</div>
	</section>

	<?php
	/* ===================== PARTE 2 = SHOWCASE de sabores =====================
	   Fondo plantilla (plantilla-showcase.png) + palabra gigante CITRUS/REBEL +
	   texto izquierda + pills derecha + flechas que cambian la lata (Citrus <->
	   Rebel). El AGUA (agua_realzada.mp4) se queda detras de la lata en ambos
	   sabores. Todo el contenido de cada sabor esta en el DOM y se muestra/oculta
	   con [data-flavor]; al cambiar, el que aparece se anima (paso de display:none
	   a visible -> re-dispara la animacion CSS). Las flechas solo cambian el
	   data-flavor (js/spirup.js). */
	$sc = array(
		'citrus' => array(
			'word'  => 'CITRUS',
			'title' => 'CITRUS BLUE',
			'sub'   => 'REFRESCANTE, ENRIQUECIDA Y NATURAL:',
			'lead'  => 'energía limpia para potenciar tu día.',
			'pills' => array(
				array( 'Fresco', 'soft' ),
				array( 'Refrescante', 'lime' ),
				array( 'Ligero', 'soft' ),
				array( 'Energía natural', 'teal' ),
			),
			'can'   => 'lata-citrus.png',
			'alt'   => 'Lata Spir Up Citrus Blue',
		),
		'rebel' => array(
			'word'  => 'REBEL',
			'title' => 'REBEL BLUE',
			'sub'   => 'REFRESCANTE, ENRIQUECIDA Y NATURAL:',
			'lead'  => 'energía limpia para potenciar tu día.',
			'pills' => array(
				array( 'Intenso', 'teal' ),
				array( 'Refrescante', 'blue' ),
				array( 'Moderno', 'navy' ),
				array( 'Energía natural', 'lime' ),
			),
			'can'   => 'lata-rebel.png',
			'alt'   => 'Lata Spir Up Rebel Blue',
		),
	);
	?>
	<section class="spirup-parte2 spirup-showcase" id="una-lata" data-flavor="citrus">
		<img class="spirup-showcase__bg" src="<?php echo esc_url( $img . '/plantilla-showcase.png' ); ?>" alt="" aria-hidden="true">

		<?php foreach ( $sc as $key => $f ) : ?>
			<span class="spirup-showcase__wm is-<?php echo esc_attr( $key ); ?>" aria-hidden="true"><?php echo esc_html( $f['word'] ); ?></span>
		<?php endforeach; ?>

		<div class="spirup-showcase__inner">
			<?php /* Columna izquierda: descripcion del sabor */ ?>
			<div class="spirup-showcase__side spirup-showcase__side--left">
				<?php foreach ( $sc as $key => $f ) : ?>
					<div class="spirup-showcase__desc is-<?php echo esc_attr( $key ); ?>">
						<strong><?php echo esc_html( $f['title'] ); ?></strong>
						<span class="spirup-showcase__descsub"><?php echo esc_html( $f['sub'] ); ?></span>
						<p><?php echo esc_html( $f['lead'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<?php /* Centro: escenario con el agua (video) detras y las 2 latas */ ?>
			<div class="spirup-showcase__stage" data-splash-video>
				<button type="button" class="spirup-showcase__arrow spirup-showcase__arrow--prev" data-flavor-prev aria-label="Sabor anterior">&#8249;</button>

				<video class="spirup-parte2__video" muted playsinline preload="auto" aria-hidden="true">
					<source src="<?php echo esc_url( $img . '/agua_realzada.mp4' ); ?>" type="video/mp4">
				</video>

				<?php foreach ( $sc as $key => $f ) : ?>
					<img class="spirup-showcase__can is-<?php echo esc_attr( $key ); ?>"
						src="<?php echo esc_url( $img . '/' . $f['can'] ); ?>"
						alt="<?php echo esc_attr( $f['alt'] ); ?>">
				<?php endforeach; ?>

				<button type="button" class="spirup-showcase__arrow spirup-showcase__arrow--next" data-flavor-next aria-label="Siguiente sabor">&#8250;</button>
			</div>

			<?php /* Columna derecha: pills del sabor */ ?>
			<div class="spirup-showcase__side spirup-showcase__side--right">
				<?php foreach ( $sc as $key => $f ) : ?>
					<div class="spirup-showcase__pills is-<?php echo esc_attr( $key ); ?>">
						<?php foreach ( $f['pills'] as $p ) : ?>
							<span class="spirup-pill spirup-pill--<?php echo esc_attr( $p[1] ); ?>"><?php echo esc_html( $p[0] ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== Franja CTA amarilla ===================== */ ?>
	<section class="spirup-cta">
		<div class="spirup-cta__inner">
			<h3 class="spirup-cta__title">¿Listo para probarlo?</h3>
			<div class="spirup-cta__actions">
				<a class="spirup-cta__btn spirup-cta__btn--solid" href="#productos">Pedir ahora ↗</a>
				<a class="spirup-cta__btn spirup-cta__btn--ghost" href="#por-que">Más detalles</a>
			</div>
		</div>
	</section>

	<?php
	/* ===================== BLOQUE CONECTADO (desktop >820px): parte4 + Ingredientes + Del cultivo + Elige EN UNA SOLA IMAGEN (Group 68) =====================
	   Se usa la imagen COMPLETA (seccion68.png) tal cual; encima solo va lo dinamico:
	   texto de parte4, el diagrama de "Del cultivo" y las tarjetas de producto reales
	   sobre los 4 marcos horneados de "Elige". */
	$bp_cart_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.2 12.2a1.5 1.5 0 0 0 1.5 1.3h8.4a1.5 1.5 0 0 0 1.5-1.2L21.5 7H6"/></svg>';
	$bp_can_svg  = '<svg viewBox="0 0 48 96" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"><rect x="10" y="6" width="28" height="84" rx="8"/><path d="M14 6c0-2 2-3 4-3h12c2 0 4 1 4 3"/><path d="M12 26h24"/></svg>';
	$bp_products = function_exists( 'wc_get_products' )
		? wc_get_products( array( 'status' => 'publish', 'limit' => 4, 'orderby' => 'menu_order date', 'order' => 'ASC' ) )
		: array();
	$bp_centers = array( 15.35, 38.45, 61.55, 84.58 ); // centros de los 4 marcos horneados (Group 75)
	?>
	<section class="spirup-bloque" id="por-que">
		<div class="spirup-bloque__inner">
			<img class="spirup-bloque__img" src="<?php echo esc_url( $img . '/Group 75.png' ); ?>" alt="">
			<div class="spirup-bloque__p4">
				<h2 class="spirup-bloque__title">El potencial de las microalgas, en una bebida que sí disfrutarás</h2>
				<ul class="spirup-bloque__list">
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una gaseosa común</li>
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una bebida energizante</li>
					<li class="is-yes"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9 17.5 20 6"/></svg></span>Es una nueva forma de nutrirte y disfrutar</li>
				</ul>
				<p class="spirup-bloque__claim">SPIR UP no compiten contra otras gaseosas,<br><strong>SPIR UP crea una nueva categoría</strong></p>
			</div>
			<?php /* Diagrama de "Del cultivo": 3 circulos VACIOS (sin iconos) + flechas, alineados sobre las etiquetas horneadas (centros 16.8 / 46 / 74.9%) */ ?>
			<div class="spirup-bloque__diagram" aria-hidden="true">
				<span class="c c1"></span>
				<span class="ar ar1"></span>
				<span class="c c2"></span>
				<span class="ar ar2"></span>
				<span class="c c3"></span>
			</div>
			<?php /* Tarjetas de producto reales encimadas sobre los 4 marcos de "Elige" */ ?>
			<div class="spirup-bloque__products">
				<?php $bp_i = 0; foreach ( $bp_products as $product ) :
					if ( ! isset( $bp_centers[ $bp_i ] ) ) { break; }
					$pid = $product->get_id();
					?>
					<article class="bp" style="left:<?php echo esc_attr( $bp_centers[ $bp_i ] ); ?>%;">
						<div class="bp__img">
							<?php if ( $product->get_image_id() ) : ?>
								<?php echo $product->get_image( 'large' ); // phpcs:ignore ?>
							<?php else : ?>
								<span class="bp__ph"><?php echo $bp_can_svg; // phpcs:ignore ?></span>
							<?php endif; ?>
						</div>
						<div class="bp__meta">
							<div class="bp__info">
								<h3><?php echo esc_html( $product->get_name() ); ?></h3>
								<span class="bp__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
							</div>
							<a href="?add-to-cart=<?php echo esc_attr( $pid ); ?>"
								data-product_id="<?php echo esc_attr( $pid ); ?>" data-quantity="1"
								class="bp__cart add_to_cart_button ajax_add_to_cart" rel="nofollow"
								aria-label="Añadir <?php echo esc_attr( $product->get_name() ); ?> al carrito">
								<?php echo $bp_cart_svg; // phpcs:ignore ?>
							</a>
						</div>
					</article>
				<?php $bp_i++; endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== MOVIL (<=820px): flujo HTML de las 3 secciones ===================== */ ?>
	<div class="spirup-mobileflow">
	<?php /* ===================== PARTE 4: Potencial de las microalgas ===================== */ ?>
	<section class="spirup-parte4">
		<div class="spirup-parte4__inner">
			<div class="spirup-parte4__media">
				<img src="<?php echo esc_url( $img . '/sesion4-beach.png' ); ?>"
					alt="Lata Spir Up Citrus Blue con gafas de sol junto a una piscina">
			</div>
			<div class="spirup-parte4__text">
				<h2 class="spirup-parte4__title">El potencial de las microalgas, en una bebida que sí disfrutarás</h2>
				<ul class="spirup-parte4__list">
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una gaseosa común</li>
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una bebida energizante</li>
					<li class="is-yes"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9 17.5 20 6"/></svg></span>Es una nueva forma de nutrirte y disfrutar</li>
				</ul>
				<p class="spirup-parte4__claim">SPIR UP no compiten contra otras gaseosas,<br><strong>SPIR UP crea una nueva categoría</strong></p>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 3: Ingredientes (va despues de parte4) ===================== */ ?>
	<section class="spirup-parte3" id="beneficios">
		<?php /* Escritorio: imagen con la CURVA superior que corta la figura de arriba
			(parte4), el swirl y las tarjetas. Transparente arriba/abajo. */ ?>
		<img class="spirup-parte3__img" src="<?php echo esc_url( $img . '/parte3-ingredientes.png' ); ?>"
			alt="Ingredientes con propósito, nada de relleno">
		<?php /* Movil: version HTML con texto legible (la imagen ancha no calza). */ ?>
		<div class="spirup-parte3__inner">
			<h2 class="spirup-parte3__title">Ingredientes con propósito,<br>nada de relleno</h2>
			<div class="spirup-parte3__grid">
				<div class="spirup-p3card">
					<span class="spirup-p3card__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 21c-2.2-4 1.6-6.5-.2-11"/><path d="M12 21c-1-6 2.4-8.5.2-14"/><path d="M17 21c2-4-1.4-6.6.4-11"/></svg></span>
					<strong>Microalgas</strong>
					<span>Bioactivos funcionales de origen natural</span>
				</div>
				<div class="spirup-p3card">
					<span class="spirup-p3card__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="14" r="3.4"/><circle cx="15.5" cy="9.5" r="2.2"/><circle cx="16.5" cy="16.5" r="1.4"/></svg></span>
					<strong>Agua gasificada</strong>
					<span>Contenido controlado de sodio</span>
				</div>
				<div class="spirup-p3card">
					<span class="spirup-p3card__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19C5 11 11 6 19 6c0 8-6 13-14 13z"/><path d="M8.5 15.5c2.2-3.2 5-5.2 8.2-6.2"/></svg></span>
					<strong>Extractos naturales</strong>
					<span>Sin saborizantes artificiales</span>
				</div>
				<div class="spirup-p3card">
					<span class="spirup-p3card__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/><path d="M3 21 21 3"/></svg></span>
					<strong>Sin azúcar añadida</strong>
					<span>Dulzor equilibrado sin culpa</span>
				</div>
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
				<div class="spirup-cultivo__flow">
					<img class="spirup-cultivo__diagram" src="<?php echo esc_url( $img . '/cultivo-diagrama.png' ); ?>"
						alt="El origen, el desarrollo y el resultado de Spir Up">
					<div class="spirup-cultivo__steps">
						<p>Exploramos el potencial de las microalgas y sus compuestos bioactivos.</p>
						<p>Trabajamos en la formulación para equilibrar funcionalidad, sabor y una experiencia refrescante.</p>
						<p>Una propuesta peruana que acerca la ciencia a la vida cotidiana.</p>
					</div>
				</div>
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
			<h2 class="spirup-parte8__title">Elige cómo quieres tu SPIR UP</h2>
			<div class="spirup-parte8__grid">
				<?php foreach ( $sp8_products as $product ) :
					$pid  = $product->get_id();
					$meta = wp_strip_all_tags( $product->get_short_description() );
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
								<h3><?php echo esc_html( $product->get_name() ); ?></h3>
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
			<h2 class="spirup-parte9__title">Únete al lanzamiento exclusivo de SPIR UP</h2>
			<p class="spirup-parte9__lead">La primera producción de Spir Up estará disponible para un grupo selecto de personas antes de su lanzamiento oficial.</p>
			<p class="spirup-parte9__lead">Déjanos tu correo y recibe acceso prioritario, novedades exclusivas y la oportunidad de conseguir las primeras unidades.</p>
			<form class="spirup-parte9__form" action="#" method="post" onsubmit="return false;">
				<input type="email" name="email" placeholder="Tu correo electrónico" aria-label="Tu correo electrónico" required>
				<button type="submit" aria-label="Reservar">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 15 12 9 18"/></svg>
				</button>
			</form>
			<p class="spirup-parte9__note">Sin spam. Solo te escribimos cuando llegue tu turno.</p>
		</div>
	</section>

</main>

<?php
get_footer();
