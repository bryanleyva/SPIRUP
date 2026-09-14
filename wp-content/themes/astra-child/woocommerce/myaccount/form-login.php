<?php
/**
 * Acceso / registro de "Mi cuenta" con la imagen de SPIR UP.
 *
 * Sustituye a woocommerce/templates/myaccount/form-login.php: mismos campos y
 * hooks (nonce incluido), pero maquetado como una tarjeta centrada con el logo.
 *
 * @package SPIRUP
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$spirup_reg = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$spirup_img = get_stylesheet_directory_uri() . '/imagenes';
?>

<div class="spirup-auth<?php echo $spirup_reg ? ' has-register' : ''; ?>">

	<div class="spirup-auth__card">
		<a class="spirup-auth__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Spir Up - Inicio">
			<img src="<?php echo esc_url( $spirup_img . '/logo-spirup.png' ); ?>" srcset="<?php echo esc_url( $spirup_img . '/logo-spirup.png' ); ?> 1x, <?php echo esc_url( $spirup_img . '/logo-spirup@2x.png' ); ?> 2x" width="154" height="186" alt="Spir Up">
		</a>
		<h1 class="spirup-auth__title"><?php esc_html_e( 'Iniciar sesión', 'astra-child' ); ?></h1>
		<p class="spirup-auth__sub"><?php esc_html_e( 'Entra a tu cuenta para ver tus pedidos.', 'astra-child' ); ?></p>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

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

		<?php if ( $spirup_reg ) : ?>
			<div class="spirup-auth__sep"><span><?php esc_html_e( 'o', 'astra-child' ); ?></span></div>

			<form method="post" class="woocommerce-form woocommerce-form-register register spirup-auth__register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<p class="woocommerce-form-row form-row">
						<label for="reg_username"><?php esc_html_e( 'Usuario', 'astra-child' ); ?></label>
						<input type="text" class="woocommerce-Input input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>
				<?php endif; ?>

				<p class="woocommerce-form-row form-row">
					<label for="reg_email"><?php esc_html_e( 'Crear cuenta con tu correo', 'astra-child' ); ?></label>
					<input type="email" class="woocommerce-Input input-text" name="email" id="reg_email" autocomplete="email" placeholder="tucorreo@ejemplo.com" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<p class="woocommerce-form-row form-row">
						<label for="reg_password"><?php esc_html_e( 'Contraseña', 'astra-child' ); ?></label>
						<input type="password" class="woocommerce-Input input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="••••••••" />
					</p>
				<?php else : ?>
					<p class="spirup-auth__note"><?php esc_html_e( 'Te enviaremos un enlace por correo para crear tu contraseña.', 'astra-child' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit spirup-auth__btn spirup-auth__btn--ghost" name="register" value="<?php esc_attr_e( 'Crear cuenta', 'astra-child' ); ?>"><?php esc_html_e( 'Crear cuenta', 'astra-child' ); ?></button>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>
		<?php endif; ?>

		<p class="spirup-auth__back"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver a la tienda', 'astra-child' ); ?></a></p>
	</div>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
