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
		<?php /* Titulo/subtitulo como HTML: solo en MOVIL (para agrandarlo y centrarlo
			SIN encimarse sobre el splash). En escritorio el texto va horneado en la imagen.
			Va FUERA de __inner para no afectar la posicion de la lata. */ ?>
		<div class="spirup-parte2__head">
			<h2 class="spirup-parte2__title">Una lata con ciencia adentro</h2>
			<p class="spirup-parte2__sub"><strong>355 ml de bebida gasificada</strong> formulada con un bioactivo reconocido por su potencial antioxidante.</p>
		</div>

		<div class="spirup-parte2__inner">
			<?php /* Composicion (agua + badges + onda). En MOVIL se usa la version
				SIN texto (parte2-clean.png) para no duplicar el titulo HTML. */ ?>
			<picture>
				<source media="(max-width: 820px)" srcset="<?php echo esc_url( $img . '/parte2-clean.png' ); ?>">
				<img class="spirup-figura__img"
					src="<?php echo esc_url( $img . '/parte2 (2).png' ); ?>"
					alt="Una lata con ciencia adentro. 355 ml de bebida gasificada formulada con un bioactivo reconocido por su potencial antioxidante.">
			</picture>

			<?php /* Lata 3D (Citrus Blue) fija de frente, encajada en el hueco del agua.
				En ESCRITORIO se ve el 3D interactivo; en MOVIL (donde Safari iOS no
				renderiza WebGL de forma fiable) se muestra la lata estatica de abajo. */ ?>
			<div id="spirup-lata3d" class="spirup-lata3d" aria-label="Lata Spir Up Citrus Blue"></div>

			<?php /* Lata estatica: fallback para movil (captura del propio 3D = identica). */ ?>
			<img class="spirup-lata2d"
				src="<?php echo esc_url( $img . '/lata-citrus.png' ); ?>"
				alt="Lata Spir Up Citrus Blue" aria-hidden="true">
		</div>
	</section>

	<?php /* ===================== PARTE 3: Ingredientes ===================== */ ?>
	<section class="spirup-parte3">
		<?php /* Imagen compuesta (ola/corte + panel teal + remolino + tarjetas con icono).
		         El texto va superpuesto en % para calzar a cualquier ancho. */ ?>
		<img class="spirup-parte3__img" src="<?php echo esc_url( $img . '/parte3queescomplicada.png' ); ?>" alt="" aria-hidden="true">

		<div class="spirup-parte3__head">
			<span class="spirup-parte3__badge">Que contiene</span>
			<h2 class="spirup-parte3__title">Ingredientes con propósito, nada de relleno</h2>
		</div>

		<div class="spirup-parte3__cards">
			<div class="spirup-p3card"><strong>Microalgas</strong><span>Bioactivos funcionales de origen natural</span></div>
			<div class="spirup-p3card"><strong>Agua gasificada</strong><span>Contenido controlado de sodio</span></div>
			<div class="spirup-p3card"><strong>Extractos naturales</strong><span>Sin saborizantes artificiales</span></div>
			<div class="spirup-p3card"><strong>Sin azúcar añadida</strong><span>Dulzor equilibrado sin culpa</span></div>
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

	<?php /* ===================== PARTE 4: Potencial de las microalgas ===================== */ ?>
	<section class="spirup-parte4" id="por-que">
		<div class="spirup-parte4__inner">
			<div class="spirup-parte4__media">
				<img src="<?php echo esc_url( $img . '/Vector 8.png' ); ?>"
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
				<a class="spirup-parte4__btn" href="#beneficios">Conoce la ciencia detrás ↗</a>
			</div>
		</div>
	</section>

	<?php
	/* ===================== PARTE 5: Energía que dura ===================== */
	$sp5_features = array(
		array(
			'svg'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
			'title' => 'Ficocianina',
			'desc'  => 'Bioactivo natural de microalgas.',
		),
		array(
			'svg'   => '<path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"/>',
			'title' => 'Antioxidantes',
			'desc'  => 'Ayudan a proteger tus células.',
		),
		array(
			'svg'   => '<rect x="2" y="7.5" width="16" height="9" rx="2.5"/><path d="M21.5 10.5v3"/><path d="M6.5 12h4"/><path d="M8.5 10v4"/>',
			'title' => 'Ciencia aplicada',
			'desc'  => 'Investigación convertida en una bebida.',
		),
		array(
			'svg'   => '<circle cx="12" cy="12" r="9.5"/><path d="M2.5 12h19"/><path d="M12 2.5c2.6 2.5 4 6 4 9.5s-1.4 7-4 9.5c-2.6-2.5-4-6-4-9.5s1.4-7 4-9.5z"/>',
			'title' => 'Sin azúcar',
			'desc'  => 'Baja en calorías y deliciosa.',
		),
		array(
			'svg'   => '<rect x="1.6" y="9" width="3.4" height="6" rx="1"/><rect x="19" y="9" width="3.4" height="6" rx="1"/><path d="M5 12h14"/><path d="M6.6 10.4v3.2"/><path d="M17.4 10.4v3.2"/>',
			'title' => 'Microalgas',
			'desc'  => 'Innovación inspirada en la naturaleza.',
		),
		array(
			'svg'   => '<path d="M7 19H4.8a1.8 1.8 0 0 1-1.55-2.7L7.2 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.55-2.66l-1.23-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.3 13.6 7.2 9.5 3.1 10.6"/><path d="m9.34 5.81 1.1-1.9a1.8 1.8 0 0 1 3.1 0l3.94 6.84"/><path d="m13.38 9.63 4.1 1.1 1.1-4.1"/>',
			'title' => 'Sostenibilidad',
			'desc'  => 'Pensada para ti y el planeta.',
		),
	);
	?>
	<section class="spirup-parte5" id="beneficios">
		<div class="spirup-parte5__inner">
			<h2 class="spirup-parte5__title">Refrescante por naturaleza.<br>Respaldada por la ciencia.</h2>
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
			<img src="<?php echo esc_url( $img . '/Group 50.png' ); ?>"
				alt="Latas Spir Up Rebel Blue y Citrus Blue">
		</div>
		<div class="spirup-parte6__bar">
			<div class="spirup-parte6__barinner">
				<span class="spirup-parte6__slogan">Si tu día no para, Spir Up tampoco</span>
				<a class="spirup-parte6__btn" href="#productos">¿Listo para probarlo? ↗</a>
			</div>
		</div>
	</section>

	<?php /* ===================== PARTE 7: Historia + linea de tiempo ===================== */ ?>
	<section class="spirup-parte7" id="conocenos">
		<img class="spirup-parte7__wm" src="<?php echo esc_url( $img . '/Recurso 7 1.png' ); ?>" alt="" aria-hidden="true">
		<div class="spirup-parte7__inner">
			<div class="spirup-parte7__text">
				<h2 class="spirup-parte7__title">La ciencia también<br>puede ser refrescante</h2>
				<p><strong>Spir Up</strong> nació de una pregunta simple: ¿cómo transformar la investigación científica en una bebida que la gente realmente disfrute?</p>
				<p>Tras múltiples etapas de formulación y aprendizaje, desarrollamos una propuesta refrescante elaborada con <strong>ficocianina</strong>, un valioso nutriente extraído de microalgas. Somos una marca peruana convencida de que la ciencia impacta más cuando se integra a la vida cotidiana. Creamos experiencias que conectan la innovación científica con tu bienestar, porque creemos firmemente que <strong>cuidarte también se debe disfrutar.</strong></p>
			</div>
			<div class="spirup-parte7__timeline">
				<div class="spirup-tl">
					<h3>El origen</h3>
					<p>Encontramos una oportunidad en las microalgas.</p>
				</div>
				<div class="spirup-tl">
					<h3>El desarrollo</h3>
					<p>Investigación, formulación y aprendizaje.</p>
				</div>
				<div class="spirup-tl">
					<h3>El resultado</h3>
					<p>Una bebida innovadora lista para disfrutar.</p>
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
