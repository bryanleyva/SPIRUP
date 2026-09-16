<?php
/**
 * Acceso / registro de "Mi cuenta" con la imagen de SPIR UP.
 *
 * Sustituye a woocommerce/templates/myaccount/form-login.php: mismos campos y
 * hooks (nonce incluido), maquetado como una tarjeta centrada con el logo y
 * dos pestanas: "Iniciar sesion" y "Crear cuenta" (misma tarjeta, mismo estilo).
 *
 * @package SPIRUP
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$spirup_img = get_stylesheet_directory_uri() . '/imagenes';
// Si venimos de un intento de registro con error, abrimos esa pestana.
$spirup_tab = ( ! empty( $_POST['register'] ) ) ? 'register' : 'login';   // phpcs:ignore WordPress.Security.NonceVerification
?>

<div class="spirup-auth has-register">

	<div class="spirup-auth__card"
		data-title-login="Tu cuenta" data-sub-login="Entra o crea tu cuenta para ver y seguir tus pedidos."
		data-title-register="Crear cuenta" data-sub-register="Regístrate para disfrutar de todos los beneficios de Spir Up.">
		<a class="spirup-auth__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Spir Up - Inicio">
			<img src="<?php echo esc_url( $spirup_img . '/logo-spirup.png' ); ?>" srcset="<?php echo esc_url( $spirup_img . '/logo-spirup.png' ); ?> 1x, <?php echo esc_url( $spirup_img . '/logo-spirup@2x.png' ); ?> 2x" width="154" height="186" alt="Spir Up">
		</a>
		<h1 class="spirup-auth__title" data-spirup-auth-title><?php esc_html_e( 'Tu cuenta', 'astra-child' ); ?></h1>
		<p class="spirup-auth__sub" data-spirup-auth-sub><?php esc_html_e( 'Entra o crea tu cuenta para ver y seguir tus pedidos.', 'astra-child' ); ?></p>

		<p class="spirup-auth__error" data-spirup-auth-error hidden></p>

		<div class="spirup-auth__tabs" role="tablist">
			<button type="button" class="spirup-auth__tab<?php echo 'login' === $spirup_tab ? ' is-active' : ''; ?>" data-spirup-tab="login" role="tab"><?php esc_html_e( 'Iniciar sesión', 'astra-child' ); ?></button>
			<button type="button" class="spirup-auth__tab<?php echo 'register' === $spirup_tab ? ' is-active' : ''; ?>" data-spirup-tab="register" role="tab"><?php esc_html_e( 'Crear cuenta', 'astra-child' ); ?></button>
		</div>

		<form class="woocommerce-form woocommerce-form-login login spirup-auth__panel<?php echo 'login' === $spirup_tab ? ' is-active' : ''; ?>" data-spirup-panel="login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row form-row">
				<label for="username"><?php esc_html_e( 'Correo electrónico o usuario', 'astra-child' ); ?></label>
				<input type="text" class="woocommerce-Input input-text" name="username" id="username" autocomplete="username"
					placeholder="tucorreo@ejemplo.com"
					value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<p class="woocommerce-form-row form-row">
				<label for="password"><?php esc_html_e( 'Contraseña', 'astra-child' ); ?></label>
				<input class="woocommerce-Input input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="spirup-auth__row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox spirup-auth__remember">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<span><?php esc_html_e( 'Recuérdame', 'astra-child' ); ?></span>
				</label>
				<a class="spirup-auth__lost" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( '¿Olvidaste tu contraseña?', 'astra-child' ); ?></a>
			</div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<button type="submit" class="woocommerce-button button woocommerce-form-login__submit spirup-auth__btn" name="login" value="<?php esc_attr_e( 'Entrar', 'astra-child' ); ?>"><?php esc_html_e( 'Entrar', 'astra-child' ); ?></button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

		<?php /* El registro usa el MISMO formulario que la ventana del checkout (AJAX
			propio): asi los dos piden y guardan exactamente los mismos datos. */ ?>
		<form class="spirup-auth__panel<?php echo 'register' === $spirup_tab ? ' is-active' : ''; ?>" data-spirup-panel="register" data-spirup-auth-form="register" data-spirup-auth-redirect="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" novalidate>
			<?php spirup_register_fields( 'reg' ); ?>
			<button type="submit" class="spirup-auth__btn">Registrarme</button>
			<p class="spirup-auth__swap">¿Ya tienes una cuenta? <button type="button" data-spirup-tab="login">Inicia sesión</button></p>
		</form>

		<p class="spirup-auth__back"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver a la tienda', 'astra-child' ); ?></a></p>
	</div>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
