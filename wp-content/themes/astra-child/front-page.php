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
			src="<?php echo esc_url( $img . '/parte1f.png' ); ?>"
			alt="¡Un sorbo de vitalidad, un sorbo de Spir Up!">
		<a class="spirup-figura__cta spirup-btn spirup-btn--orange" href="#reservar">Pruébala ahora ↗</a>
	</section>

	<?php /* ===================== PARTE 2 ===================== */ ?>
	<section class="spirup-parte2" id="una-lata">
		<div class="spirup-parte2__head">
			<h2 class="spirup-parte2__title">Una lata con ciencia adentro</h2>
			<p class="spirup-parte2__sub"><strong>355 ml de bebida gasificada</strong> formulada con un bioactivo reconocido por su potencial antioxidante.</p>
		</div>

		<?php /* Escenario de la lata: el render nuevo (lata-spir-up 1 (2).png) +
			una ola de agua SIMULADA que rompe por detras de la lata al entrar la
			seccion en pantalla, y que despues sigue rompiendo en ciclo.
			js/spirup.js agrega .is-in a [data-water-stage] cuando la seccion entra;
			js/spirup-splash.js corre la simulacion en WebGL, por detras, dentro de
			.spirup-parte2__water. Sin WebGL cae a la foto splash-agua.png.
			Ajustes de aqui abajo:
			  spread = ancho de la escena, medido en anchos de lata
			  x / y  = de donde sale el chorro, sobre la lata (0-1)
			  jet    = fuerza de la ola
			  flow   = agua de fondo entre ola y ola
			  shine  = intensidad de los reflejos
			  tint   = color del agua (el aqua de la lata, #56b6bd, algo mas vivo) */ ?>
		<div class="spirup-parte2__stage" data-water-stage>
			<div class="spirup-parte2__water" aria-hidden="true"
				data-splash-src="<?php echo esc_url( $img . '/splash-agua' ); ?>"
				data-splash-spread="3.3"
				data-splash-x="0.50"
				data-splash-y="0.60"
				data-splash-jet="1"
				data-splash-flow="1"
				data-splash-shine="1"
				data-splash-tint="#62d3d6"></div>
			<img class="spirup-parte2__can"
				src="<?php echo esc_url( $img . '/lata-spir-up 1 (2).png' ); ?>"
				alt="Lata Spir Up Citrus Blue">
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

	<?php /* ===================== BLOQUE CONECTADO (desktop >820px): parte4 + Ingredientes + Del cultivo en una sola imagen ===================== */ ?>
	<section class="spirup-bloque" id="por-que">
		<div class="spirup-bloque__inner">
			<img class="spirup-bloque__img" src="<?php echo esc_url( $img . '/bloque-conectado.png' ); ?>" alt="">
			<div class="spirup-bloque__p4">
				<h2 class="spirup-bloque__title">El potencial de las microalgas, en una bebida que sí disfrutarás</h2>
				<ul class="spirup-bloque__list">
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una gaseosa común</li>
					<li class="is-no"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></span>No es una bebida energizante</li>
					<li class="is-yes"><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9 17.5 20 6"/></svg></span>Es una nueva forma de nutrirte y disfrutar</li>
				</ul>
				<p class="spirup-bloque__claim">SPIR UP no compiten contra otras gaseosas,<br><strong>SPIR UP crea una nueva categoría</strong></p>
			</div>
			<?php /* Descripciones de "Del cultivo" encimadas bajo cada label (no venian en la imagen) */ ?>
			<div class="spirup-bloque__steps">
				<p class="s1">Exploramos el<br>potencial de las<br>microalgas y sus<br>compuestos<br>bioactivos.</p>
				<p class="s2">Trabajamos en la<br>formulación para<br>equilibrar&nbsp; funcionalidad,<br>sabor y una experiencia<br>refrescante.</p>
				<p class="s3">Exploramos el<br>potencial de las<br>microalgas y sus<br>compuestos<br>bioactivos.</p>
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

	<?php /* ===================== Del cultivo a la planta (despues de Ingredientes) ===================== */ ?>
	<section class="spirup-cultivo" id="conocenos">
		<div class="spirup-cultivo__inner">
			<div class="spirup-cultivo__body">
				<h2 class="spirup-cultivo__title">Del cultivo a la planta</h2>
				<p class="spirup-cultivo__sub">Cada etapa agrega valor</p>
				<?php /* Diagrama (circulos + flechas + labels El origen/desarrollo/resultado). Labels en los tercios. */ ?>
				<div class="spirup-cultivo__flow">
					<img class="spirup-cultivo__diagram" src="<?php echo esc_url( $img . '/cultivo-diagrama.png' ); ?>"
						alt="El origen, el desarrollo y el resultado de Spir Up">
					<div class="spirup-cultivo__steps">
						<p>Exploramos el potencial de las microalgas y sus compuestos bioactivos.</p>
						<p>Trabajamos en la formulación para equilibrar funcionalidad, sabor y una experiencia refrescante.</p>
						<p>Exploramos el potencial de las microalgas y sus compuestos bioactivos.</p>
					</div>
				</div>
			</div>
			<div class="spirup-cultivo__media">
				<img src="<?php echo esc_url( $img . '/cultivo-foto.png' ); ?>"
					alt="Biotecnóloga trabajando en el laboratorio de Spir Up">
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
