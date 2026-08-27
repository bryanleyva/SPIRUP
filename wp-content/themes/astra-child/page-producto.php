<?php
/**
 * Plantilla de pagina: Producto (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "producto".
 * Usa la imagen group 49 (1).png de fondo y encima solo el texto (transparente en la imagen).
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_stylesheet_directory_uri() . '/imagenes';

/* Descripciones de las tarjetas de beneficios (bajo cada label horneado) */
$benef = array(
	array( 18.8, 'Bioactivo natural de microalgas.' ),
	array( 50.0, 'Ayudan a proteger tus células.' ),
	array( 81.5, 'Investigación convertida en una bebida.' ),
);
$benef2 = array(
	array( 18.8, 'Baja en calorías y deliciosa.' ),
	array( 81.5, 'Pensada para ti y el planeta.' ),
);

/* Paneles de producto: y = centro vertical de la lata (% de la imagen) */
$panels = array(
	array( 'flavor' => 'citrus', 'type' => 'intro',   'y' => 40.0, 'extractos' => '' ),
	array( 'flavor' => 'citrus', 'type' => 'details', 'y' => 56.0, 'extractos' => 'El poder de la naturaleza en el limón y la hierba luisa' ),
	array( 'flavor' => 'rebel',  'type' => 'intro',   'y' => 74.0, 'extractos' => '' ),
	array( 'flavor' => 'rebel',  'type' => 'details', 'y' => 91.5, 'extractos' => 'El poder de la naturaleza en el blueberry y el limón' ),
);

get_header();
?>

<main class="spirup-main">
	<section class="spirup-prodimg">
		<div class="spirup-prodimg__wrap">
			<img class="spirup-prodimg__bg" src="<?php echo esc_url( $img . '/Group 49 (1).png' ); ?>" alt="Sabores Spir Up Citrus Blue y Rebel Blue">

			<?php /* Anclas para los botones "Explora nuestros sabores" */ ?>
			<span id="citrus" class="ov-anchor" style="top:30%;"></span>
			<span id="rebel" class="ov-anchor" style="top:64%;"></span>

			<?php /* --- Beneficios: titulo + descripciones --- */ ?>
			<div class="ov ov-cabecera" style="top:9.6%;left:50%;">
				<h2 class="ov-title">Refrescante por naturaleza.<br>Respaldada por la ciencia.</h2>
				<p class="ov-sub">No te pedimos que dejes de tomar café o gaseosa. Solo que pruebes una alternativa que te da más y te quita menos.</p>
			</div>
			<?php foreach ( $benef as $b ) : ?>
				<p class="ov ov-benefdesc" style="top:20.4%;left:<?php echo esc_attr( $b[0] ); ?>%;"><?php echo esc_html( $b[1] ); ?></p>
			<?php endforeach; ?>
			<?php foreach ( $benef2 as $b ) : ?>
				<p class="ov ov-benefdesc" style="top:26.6%;left:<?php echo esc_attr( $b[0] ); ?>%;"><?php echo esc_html( $b[1] ); ?></p>
			<?php endforeach; ?>

			<?php /* --- Paneles de producto --- */ ?>
			<?php foreach ( $panels as $p ) :
				$y   = $p['y'];
				$acc = 'citrus' === $p['flavor'] ? 'is-citrus' : 'is-rebel';
				if ( 'intro' === $p['type'] ) : ?>
					<?php /* Izq: descripcion */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y ); ?>%;left:13%;">
						<strong class="ov-h">Bebida carbonatada sin&nbsp;azúcar con ficocianina</strong>
						<span class="ov-p">un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</span>
					</div>
					<?php /* Der: explora */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 2 ); ?>%;left:71%;">
						<span class="ov-lbl">Explora nuestros sabores</span>
						<a class="ov-tab<?php echo 'rebel' === $p['flavor'] ? ' is-on' : ''; ?>" href="#rebel">Rebel Blue</a>
						<a class="ov-tab<?php echo 'citrus' === $p['flavor'] ? ' is-on' : ''; ?>" href="#citrus">Citrus Blue</a>
					</div>
				<?php else : ?>
					<?php /* Izq: 2 atributos */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 4.2 ); ?>%;left:13%;">
						<strong class="ov-h">Agua gasificada</strong><span class="ov-p">Frescura que se siente.</span>
					</div>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y + 4.2 ); ?>%;left:13%;">
						<strong class="ov-h">Ficocianina</strong><span class="ov-p">Antioxidantes de origen&nbsp;natural.</span>
					</div>
					<?php /* Der: 2 atributos (Vitamina C en naranja) */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 4.2 ); ?>%;left:71%;">
						<strong class="ov-h">Extractos naturales</strong><span class="ov-p"><?php echo esc_html( $p['extractos'] ); ?></span>
					</div>
					<div class="ov ov-block ov-vitc <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y + 4.2 ); ?>%;left:71%;">
						<strong class="ov-h">Vitamina C</strong><span class="ov-p">Soporte para tus defensas</span>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

		</div>
	</section>
</main>

<?php
get_footer();
