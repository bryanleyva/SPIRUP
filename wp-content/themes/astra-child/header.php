<?php
/**
 * Header personalizado de SPIRUP.
 *
 * Sustituye por completo al header de Astra para tener control total del diseno.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_stylesheet_directory_uri() . '/imagenes';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="spirup-site">

	<!-- Barra de anuncio superior -->
	<div class="spirup-topbar">
		<div class="spirup-topbar__inner">
			<span>¡LA PRIMERA BEBIDA GASIFICADA CON FICOCIANINA DE MICROALGAS!</span>
			<span class="spirup-topbar__dot">🌊</span>
			<span>ENVÍOS A NIVEL NACIONAL. <strong>¡COMPRA AHORA!</strong></span>
		</div>
	</div>

	<!-- Cabecera -->
	<header class="spirup-header">
		<div class="spirup-header__inner">

			<a class="spirup-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Spir Up - Inicio">
				<img src="<?php echo esc_url( $img . '/logo.png' ); ?>" alt="Spir Up">
			</a>

			<?php $home = esc_url( home_url( '/' ) ); ?>
			<nav class="spirup-nav" id="spirup-nav" aria-label="Menu principal">
				<a href="<?php echo $home; ?>">Spir Up</a>
				<a href="<?php echo $home; ?>#por-que">¿Por qué Spir Up?</a>
				<a href="<?php echo $home; ?>#beneficios">Beneficios</a>
				<a href="<?php echo $home; ?>#conocenos">Conócenos</a>
			</nav>

			<div class="spirup-header__actions">
				<a href="<?php echo $home; ?>#reservar" class="spirup-btn spirup-btn--orange">Reservar</a>

				<button class="spirup-burger" type="button" aria-label="Abrir menú" aria-controls="spirup-nav" aria-expanded="false">
					<span></span><span></span><span></span>
				</button>

				<a href="<?php echo $home; ?>#productos" class="spirup-iconbtn" aria-label="Ver productos">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="16.5" y1="16.5" x2="21" y2="21"></line></svg>
				</a>

				<a href="<?php echo esc_url( wp_login_url() ); ?>" class="spirup-iconbtn" aria-label="Mi cuenta">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4 3.5-6 8-6s8 2 8 6"></path></svg>
				</a>

				<a href="<?php echo $home; ?>#productos" class="spirup-iconbtn spirup-iconbtn--cart" aria-label="Carrito">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle><path d="M2 3h3l2.2 12.2a1.5 1.5 0 0 0 1.5 1.3h8.4a1.5 1.5 0 0 0 1.5-1.2L21.5 7H6"></path></svg>
				</a>
			</div>

		</div>
	</header>
