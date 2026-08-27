<?php
/**
 * Plantilla de pagina: Producto (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "producto".
 * Contenido segun group 49 (1).png: foto de latas -> Refrescante/beneficios -> Citrus Blue -> Rebel Blue.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_stylesheet_directory_uri() . '/imagenes';

/* Beneficios (Refrescante por naturaleza) */
$sp_features = array(
	array( '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>', 'Ficocianina', 'Bioactivo natural de microalgas.' ),
	array( '<path d="M12 21s-7-4.35-7-9.5A4.5 4.5 0 0 1 12 8a4.5 4.5 0 0 1 7 3.5C19 16.65 12 21 12 21z"/>', 'Antioxidantes', 'Ayudan a proteger tus células.' ),
	array( '<circle cx="12" cy="12" r="2.2"/><ellipse cx="12" cy="12" rx="10" ry="4.2"/><ellipse cx="12" cy="12" rx="10" ry="4.2" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4.2" transform="rotate(120 12 12)"/>', 'Ciencia aplicada', 'Investigación convertida en una bebida.' ),
	array( '<circle cx="12" cy="12" r="9.5"/><path d="M5 5l14 14"/><path d="M8.5 10.5c0-1.5 1.5-2.5 3.5-2.5s3.5 1 3.5 2.5S14 13 12 13.5"/>', 'Sin azúcar', 'Baja en calorías y deliciosa.' ),
	array( '<path d="M7 19H4.8a1.8 1.8 0 0 1-1.55-2.7L7.2 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.55-2.66l-1.23-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.3 13.6 7.2 9.5 3.1 10.6"/><path d="m9.34 5.81 1.1-1.9a1.8 1.8 0 0 1 3.1 0l3.94 6.84"/><path d="m13.38 9.63 4.1 1.1 1.1-4.1"/>', 'Sostenibilidad', 'Pensada para ti y el planeta.' ),
);

/* Sabores */
$sp_flavors = array(
	'citrus' => array(
		'name'      => 'Citrus Blue',
		'wm'        => 'CITRUS BLUE',
		'can'       => 'lata-citrus.png',
		'extractos' => 'El poder de la naturaleza en el limón y la hierba luisa',
	),
	'rebel'  => array(
		'name'      => 'Rebel Blue',
		'wm'        => 'REBEL BLUE',
		'can'       => 'lata-rebel.png',
		'extractos' => 'El poder de la naturaleza en el blueberry y el limón',
	),
);

get_header();
?>

<main class="spirup-main spirup-producto">

	<?php /* ===================== Foto de latas ===================== */ ?>
	<section class="spirup-producto__hero">
		<img src="<?php echo esc_url( $img . '/producto-latas.png' ); ?>" alt="Latas Spir Up Citrus Blue y Rebel Blue">
	</section>

	<?php /* ===================== Refrescante por naturaleza (beneficios) ===================== */ ?>
	<section class="spirup-parte5" id="beneficios">
		<div class="spirup-parte5__inner">
			<h2 class="spirup-parte5__title">Refrescante por naturaleza.<br>Respaldada por la ciencia.</h2>
			<p class="spirup-parte5__sub">No te pedimos que dejes de tomar café o gaseosa. Solo que pruebes una alternativa que te da más y te quita menos.</p>
			<div class="spirup-parte5__grid">
				<?php foreach ( $sp_features as $f ) : ?>
					<div class="spirup-feature">
						<span class="spirup-feature__ico">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $f[0]; // phpcs:ignore ?></svg>
						</span>
						<h3><?php echo esc_html( $f[1] ); ?></h3>
						<p><?php echo esc_html( $f[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php /* ===================== Showcase por sabor (Citrus Blue / Rebel Blue) ===================== */ ?>
	<?php
	foreach ( $sp_flavors as $key => $fl ) :
		$can_img = '<div class="spirup-showcase__stage"><img class="spirup-showcase__can is-' . esc_attr( $key ) . '" src="' . esc_url( $img . '/' . $fl['can'] ) . '" alt="Lata Spir Up ' . esc_attr( $fl['name'] ) . '"></div>';
		?>
		<?php /* Panel A: intro (descripcion + lata + explora) */ ?>
		<section class="spirup-showcase" id="<?php echo esc_attr( $key ); ?>" data-flavor="<?php echo esc_attr( $key ); ?>">
			<span class="spirup-showcase__wm"><?php echo esc_html( $fl['wm'] ); ?></span>
			<div class="spirup-showcase__inner">
				<div class="spirup-showcase__side spirup-showcase__side--left">
					<div class="spirup-showcase__desc">
						<strong>Bebida carbonatada sin&nbsp;azúcar con ficocianina</strong>
						<p>un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias</p>
					</div>
				</div>
				<?php echo $can_img; // phpcs:ignore ?>
				<div class="spirup-showcase__side spirup-showcase__side--right">
					<div class="spirup-showcase__explora">
						<p class="spirup-showcase__lbl">Explora nuestros sabores</p>
						<div class="spirup-showcase__tabs">
							<a class="spirup-showcase__tab<?php echo 'rebel' === $key ? ' is-active' : ''; ?>" href="#rebel">Rebel Blue</a>
							<a class="spirup-showcase__tab<?php echo 'citrus' === $key ? ' is-active' : ''; ?>" href="#citrus">Citrus Blue</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php /* Panel B: detalles (4 atributos + lata) */ ?>
		<section class="spirup-showcase spirup-showcase--details" data-flavor="<?php echo esc_attr( $key ); ?>">
			<span class="spirup-showcase__wm"><?php echo esc_html( $fl['wm'] ); ?></span>
			<div class="spirup-showcase__inner">
				<div class="spirup-showcase__side spirup-showcase__side--left">
					<div class="spirup-feat">
						<h3>Agua gasificada</h3>
						<p>Frescura que se siente</p>
					</div>
					<div class="spirup-feat">
						<h3>Ficocianina</h3>
						<p>Antioxidantes de origen&nbsp;natural</p>
					</div>
				</div>
				<?php echo $can_img; // phpcs:ignore ?>
				<div class="spirup-showcase__side spirup-showcase__side--right">
					<div class="spirup-feat">
						<h3>Extractos naturales</h3>
						<p><?php echo esc_html( $fl['extractos'] ); ?></p>
					</div>
					<div class="spirup-feat">
						<h3>Vitamina C</h3>
						<p>Soporte para tus defensas</p>
					</div>
				</div>
			</div>
		</section>
	<?php endforeach; ?>

</main>

<?php
get_footer();
