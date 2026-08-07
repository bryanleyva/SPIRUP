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
			src="<?php echo esc_url( $img . '/grupo figuras 2.png' ); ?>"
			alt="Un sorbo de vitalidad, un sorbo de SPIR UP.">
		<a class="spirup-figura__cta spirup-btn spirup-btn--orange" href="#reservar">Pruébala ahora ↗</a>
	</section>

	<?php /* ===================== PARTE 2 ===================== */ ?>
	<section class="spirup-parte2">
		<div class="spirup-parte2__inner">
			<?php /* Composicion (una lata + agua + badges + onda hacia el teal). */ ?>
			<img class="spirup-figura__img"
				src="<?php echo esc_url( $img . '/unalata-bg.png' ); ?>"
				alt="Una lata con ciencia adentro. 355 ml de bebida gasificada formulada con un bioactivo reconocido por su potencial antioxidante.">

			<?php /* Lata 3D girando, encajada en el hueco del agua. */ ?>
			<div id="spirup-lata3d" class="spirup-lata3d" aria-label="Lata Spir Up Citrus Blue en 3D"></div>

			<?php /* Flechas para girar la lata (como el mockup). */ ?>
			<button class="spirup-lata-arrow spirup-lata-arrow--prev" data-dir="prev" type="button" aria-label="Girar lata a la izquierda">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5 8 12l7 7"/></svg>
			</button>
			<button class="spirup-lata-arrow spirup-lata-arrow--next" data-dir="next" type="button" aria-label="Girar lata a la derecha">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
			</button>
		</div>
	</section>

	<?php /* ===================== PARTE 3: Ingredientes ===================== */ ?>
	<section class="spirup-parte3">
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

	<?php /* ===================== PARTE 4: Potencial de las microalgas ===================== */ ?>
	<section class="spirup-parte4" id="por-que">
		<div class="spirup-parte4__inner">
			<div class="spirup-parte4__media">
				<img src="<?php echo esc_url( $img . '/sesion4-beach.png' ); ?>"
					alt="Lata Spir Up Citrus Blue con gafas de sol junto a una piscina">
			</div>
			<div class="spirup-parte4__text">
				<h2 class="spirup-parte4__title">El potencial de las microalgas, en una bebida que sí disfrutarás</h2>
				<p class="spirup-parte4__desc">Años de investigación científica convertidos en una bebida gasificada, rica y fácil de disfrutar. Con un sabor refrescante, desarrollado para disfrutarlo todos los días.</p>
				<ul class="spirup-parte4__list">
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una gaseosa común</li>
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una bebida energizante</li>
					<li class="is-yes"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9 17.5 20 6"/></svg></span>Es una nueva forma de nutrirte y disfrutar</li>
				</ul>
				<a class="spirup-parte4__btn" href="#ciencia">Conoce la ciencia detrás »</a>
			</div>
		</div>
	</section>

	<?php
	/* ===================== PARTE 5: Energía que dura ===================== */
	$sp5_features = array(
		array(
			'svg'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
			'title' => 'Antioxidante de origen natural',
			'desc'  => 'Con ficocianina, un bioactivo de microalgas reconocido por su potencial antioxidante.',
		),
		array(
			'svg'   => '<circle cx="12" cy="12" r="1.6"/><ellipse cx="12" cy="12" rx="10" ry="4.3"/><ellipse cx="12" cy="12" rx="10" ry="4.3" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4.3" transform="rotate(120 12 12)"/>',
			'title' => 'Ayuda frente al estrés oxidativo',
			'desc'  => 'Los antioxidantes ayudan a proteger las células frente al estrés oxidativo generado naturalmente por el organismo.',
		),
		array(
			'svg'   => '<rect x="2" y="7.5" width="16" height="9" rx="2.5"/><path d="M21.5 10.5v3"/><path d="M6.5 12h4"/><path d="M8.5 10v4"/>',
			'title' => 'Innovación que puedes disfrutar',
			'desc'  => 'Investigación biotecnológica transformada en una bebida gasificada práctica y refrescante.',
		),
		array(
			'svg'   => '<circle cx="12" cy="12" r="9.5"/><path d="M2.5 12h19"/><path d="M12 2.5c2.6 2.5 4 6 4 9.5s-1.4 7-4 9.5c-2.6-2.5-4-6-4-9.5s1.4-7 4-9.5z"/>',
			'title' => 'Funcional por formulación',
			'desc'  => 'Sin azúcar añadida, baja en calorías y con bioactivos seleccionados con criterio científico.',
		),
		array(
			'svg'   => '<rect x="1.6" y="9" width="3.4" height="6" rx="1"/><rect x="19" y="9" width="3.4" height="6" rx="1"/><path d="M5 12h14"/><path d="M6.6 10.4v3.2"/><path d="M17.4 10.4v3.2"/>',
			'title' => 'El potencial de las microalgas',
			'desc'  => 'La ficocianina es un bioactivo de microalgas ampliamente estudiado por su potencial antioxidante y su rol en el bienestar celular.',
		),
		array(
			'svg'   => '<path d="M9 3h6"/><path d="M10 3v5.5L4.6 18a1.6 1.6 0 0 0 1.4 2.4h12A1.6 1.6 0 0 0 19.4 18L14 8.5V3"/><path d="M7.5 14h9"/>',
			'title' => 'Bienestar con propósito',
			'desc'  => 'Una bebida desarrollada bajo un enfoque de innovación y economía circular, pensada para cuidar de ti y del entorno.',
		),
	);
	?>
	<section class="spirup-parte5" id="beneficios">
		<div class="spirup-parte5__inner">
			<h2 class="spirup-parte5__title">Energía que dura, sin el bajón que ya conoces</h2>
			<p class="spirup-parte5__sub">No te pedimos que dejes de tomar café o gaseosa. Solo que pruebes una alternativa que te da más y te quita menos.</p>
			<div class="spirup-parte5__grid">
				<?php foreach ( $sp5_features as $f ) : ?>
					<div class="spirup-feature">
						<span class="spirup-feature__ico">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $f['svg']; // phpcs:ignore ?></svg>
						</span>
						<h3><?php echo esc_html( $f['title'] ); ?></h3>
						<p><?php echo esc_html( $f['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 6: Banner "Si tu dia no para" ===================== */ ?>
	<section class="spirup-parte6">
		<div class="spirup-parte6__photo">
			<img src="<?php echo esc_url( $img . '/group41-cans.jpg' ); ?>"
				alt="Latas Spir Up Rebel Blue y Citrus Blue">
		</div>
		<div class="spirup-parte6__bar">
			<div class="spirup-parte6__barinner">
				<span class="spirup-parte6__slogan">Si tu día no para, Spir Up tampoco</span>
				<a class="spirup-parte6__btn" href="#pedir">¿Listo para probarlo? ↗</a>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 7: Historia + linea de tiempo ===================== */ ?>
	<section class="spirup-parte7" id="conocenos">
		<div class="spirup-parte7__inner">
			<div class="spirup-parte7__text">
				<h2 class="spirup-parte7__title">De una pregunta de laboratorio a una lata que puedes disfrutar hoy</h2>
				<p>Spir Up nació de una pregunta simple: <strong>¿cómo llevamos el potencial de las microalgas a algo que la gente realmente quiera tomar?</strong> Después de años de investigación, llegamos a una bebida que une ciencia, sabor y bienestar. Su ingrediente clave: <strong>ficocianina</strong>, un bioactivo de microalgas reconocido por su potencial antioxidante.</p>
				<p>Somos una marca peruana que cree que cuidarse también puede ser refrescante. Hoy, ese trabajo de laboratorio cabe en tu mano.</p>
			</div>
			<div class="spirup-parte7__timeline">
				<div class="spirup-tl">
					<h3>El porqué</h3>
					<p>Vimos en las microalgas un potencial desaprovechado para el bienestar diario.</p>
				</div>
				<div class="spirup-tl">
					<h3>El cómo</h3>
					<p>Luego de múltiples etapas de investigación, formulación y validación.</p>
				</div>
				<div class="spirup-tl">
					<h3>El ahora</h3>
					<p>Una bebida gasificada lista para acompañar tu ritmo, sin tecnicismos de por medio.</p>
				</div>
			</div>
		</div>
	</section>

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
								<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore ?>
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
			<h2 class="spirup-parte9__title">Reserva tu lugar en el lanzamiento de SPIR UP</h2>
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
