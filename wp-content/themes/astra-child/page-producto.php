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

/* Paneles de producto: y = centro vertical de la lata (% de la imagen recortada 4562px).
   Los <br> fijan los mismos quiebres de linea que el diseno. */
$panels = array(
	array( 'flavor' => 'citrus', 'type' => 'intro',   'y' => 37.1, 'extractos' => '' ),
	array( 'flavor' => 'citrus', 'type' => 'details', 'y' => 53.9, 'extractos' => 'El poder de la<br>naturaleza&nbsp; en el<br>limón y la hierba&nbsp; luisa' ),
	array( 'flavor' => 'rebel',  'type' => 'intro',   'y' => 72.8, 'extractos' => '' ),
	array( 'flavor' => 'rebel',  'type' => 'details', 'y' => 91.1, 'extractos' => 'El poder de la<br>naturaleza&nbsp; en el<br>blueberry y el limón' ),
);

get_header();
?>

<main class="spirup-main">
	<section class="spirup-prodimg">
		<div class="spirup-prodimg__wrap">
			<img class="spirup-prodimg__bg" src="<?php echo esc_url( $img . '/Group 49 (1).png' ); ?>" alt="Sabores Spir Up Citrus Blue y Rebel Blue">

			<?php /* Anclas para los botones "Explora nuestros sabores" */ ?>
			<span id="citrus" class="ov-anchor" style="top:26.6%;"></span>
			<span id="rebel" class="ov-anchor" style="top:62.3%;"></span>

			<?php /* --- Beneficios: titulo + descripciones --- */ ?>
			<div class="ov ov-cabecera" style="top:9.8%;left:50%;">
				<h2 class="ov-title">Refrescante por naturaleza.<br>Respaldada por la ciencia.</h2>
				<p class="ov-sub">No te pedimos que dejes de tomar café o gaseosa. Solo que pruebes una alternativa que te da más y te quita menos.</p>
			</div>
			<?php foreach ( $benef as $b ) : ?>
				<p class="ov ov-benefdesc" style="top:16.6%;left:<?php echo esc_attr( $b[0] ); ?>%;"><?php echo esc_html( $b[1] ); ?></p>
			<?php endforeach; ?>
			<?php foreach ( $benef2 as $b ) : ?>
				<p class="ov ov-benefdesc" style="top:23.1%;left:<?php echo esc_attr( $b[0] ); ?>%;"><?php echo esc_html( $b[1] ); ?></p>
			<?php endforeach; ?>

			<?php /* --- Paneles de producto --- */ ?>
			<?php foreach ( $panels as $p ) :
				$y   = $p['y'];
				$acc = 'citrus' === $p['flavor'] ? 'is-citrus' : 'is-rebel';
				if ( 'intro' === $p['type'] ) : ?>
					<?php /* Izq: descripcion (debajo del watermark) */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 0.52 ); ?>%;left:5.5%;">
						<strong class="ov-h">Bebida<br>carbonatada<br>sin&nbsp;azúcar con<br>ficocianina</strong>
						<span class="ov-p">un potente<br>compuesto&nbsp; bioactivo<br>con&nbsp; propiedades<br>antioxidantes y<br>antiinflamatorias.</span>
					</div>
					<?php /* Der: explora (debajo del watermark) */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 0.52 ); ?>%;left:70%;">
						<span class="ov-lbl">Explora nuestros<br>sabores</span>
						<a class="ov-tab<?php echo 'rebel' === $p['flavor'] ? ' is-on' : ''; ?>" href="#rebel">Rebel Blue</a>
						<a class="ov-tab<?php echo 'citrus' === $p['flavor'] ? ' is-on' : ''; ?>" href="#citrus">Citrus Blue</a>
					</div>
				<?php else : ?>
					<?php /* Izq: 2 atributos */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 3.9 ); ?>%;left:5.5%;">
						<strong class="ov-h">Agua gasificada</strong><span class="ov-p">Frescura que se<br>siente.</span>
					</div>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y + 3.56 ); ?>%;left:5.5%;">
						<strong class="ov-h">Ficocianina</strong><span class="ov-p">Antioxidantes de<br>origen&nbsp; natural.</span>
					</div>
					<?php /* Der: 2 atributos (Vitamina C en naranja) */ ?>
					<div class="ov ov-block <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y - 3.9 ); ?>%;left:70%;">
						<strong class="ov-h">Extractos naturales</strong><span class="ov-p"><?php echo wp_kses( $p['extractos'], array( 'br' => array() ) ); ?></span>
					</div>
					<div class="ov ov-block ov-vitc <?php echo $acc; ?>" style="top:<?php echo esc_attr( $y + 3.56 ); ?>%;left:70%;">
						<strong class="ov-h">Vitamina C</strong><span class="ov-p">Soporte para tus<br>defensas</span>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

		</div>
	</section>

	<?php /* ===================== VERSION MOVIL apilada (<=820px) ===================== */ ?>
	<div class="spirup-prodmobile">
		<img class="spirup-prodmobile__latas" src="<?php echo esc_url( $img . '/producto-latas.png' ); ?>" alt="Latas Spir Up Citrus Blue y Rebel Blue">

		<section class="spirup-parte5">
			<div class="spirup-parte5__inner">
				<h2 class="spirup-parte5__title">Refrescante por naturaleza.<br>Respaldada por la ciencia.</h2>
				<p class="spirup-parte5__sub">No te pedimos que dejes de tomar café o gaseosa. Solo que pruebes una alternativa que te da más y te quita menos.</p>
				<div class="spirup-parte5__grid">
					<?php
					$mbenef = array(
						array( '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>', 'Ficocianina', 'Bioactivo natural de microalgas.' ),
						array( '<path d="M12 21s-7-4.35-7-9.5A4.5 4.5 0 0 1 12 8a4.5 4.5 0 0 1 7 3.5C19 16.65 12 21 12 21z"/>', 'Antioxidantes', 'Ayudan a proteger tus células.' ),
						array( '<circle cx="12" cy="12" r="2.2"/><ellipse cx="12" cy="12" rx="10" ry="4.2"/><ellipse cx="12" cy="12" rx="10" ry="4.2" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4.2" transform="rotate(120 12 12)"/>', 'Ciencia aplicada', 'Investigación convertida en una bebida.' ),
						array( '<circle cx="12" cy="12" r="9.5"/><path d="M5 5l14 14"/>', 'Sin azúcar', 'Baja en calorías y deliciosa.' ),
						array( '<path d="M7 19H4.8a1.8 1.8 0 0 1-1.55-2.7L7.2 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.55-2.66l-1.23-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.3 13.6 7.2 9.5 3.1 10.6"/><path d="m9.34 5.81 1.1-1.9a1.8 1.8 0 0 1 3.1 0l3.94 6.84"/><path d="m13.38 9.63 4.1 1.1 1.1-4.1"/>', 'Sostenibilidad', 'Pensada para ti y el planeta.' ),
					);
					foreach ( $mbenef as $f ) : ?>
						<div class="spirup-feature">
							<span class="spirup-feature__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $f[0]; // phpcs:ignore ?></svg></span>
							<h3><?php echo esc_html( $f[1] ); ?></h3>
							<p><?php echo esc_html( $f[2] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php
		$mflavors = array(
			'citrus' => array( 'Citrus Blue', 'CITRUS BLUE', 'lata-citrus.png', 'El poder de la naturaleza en el limón y la hierba luisa' ),
			'rebel'  => array( 'Rebel Blue', 'REBEL BLUE', 'lata-rebel.png', 'El poder de la naturaleza en el blueberry y el limón' ),
		);
		foreach ( $mflavors as $k => $f ) : ?>
			<section class="spirup-mflavor is-<?php echo esc_attr( $k ); ?>">
				<span class="spirup-mflavor__wm"><?php echo esc_html( $f[1] ); ?></span>
				<img class="spirup-mflavor__can" src="<?php echo esc_url( $img . '/' . $f[2] ); ?>" alt="Lata Spir Up <?php echo esc_attr( $f[0] ); ?>">
				<p class="spirup-mflavor__desc"><strong>Bebida carbonatada sin azúcar con ficocianina</strong> un potente compuesto bioactivo con propiedades antioxidantes y antiinflamatorias.</p>
				<div class="spirup-mflavor__grid">
					<div class="mfeat"><h3>Agua gasificada</h3><p>Frescura que se siente.</p></div>
					<div class="mfeat"><h3>Ficocianina</h3><p>Antioxidantes de origen natural.</p></div>
					<div class="mfeat"><h3>Extractos naturales</h3><p><?php echo esc_html( $f[3] ); ?></p></div>
					<div class="mfeat mfeat--vitc"><h3>Vitamina C</h3><p>Soporte para tus defensas</p></div>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
</main>

<?php
get_footer();
