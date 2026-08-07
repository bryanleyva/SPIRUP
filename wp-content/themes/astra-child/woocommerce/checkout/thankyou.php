<?php
/**
 * Pedido exitoso (order received) - SPIRUP.
 *
 * Sobrescribe woocommerce/checkout/thankyou.php con el diseno de la marca.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="spirup-thankyou">
	<div class="spirup-thankyou__card">
		<span class="spirup-thankyou__badge" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m8.5 12.5 2.5 2.5 5-5.5"/></svg>
		</span>

		<h1 class="spirup-thankyou__title">Pedido exitoso</h1>

		<?php if ( $order && $order->has_status( 'failed' ) ) : ?>
			<p class="spirup-thankyou__text">No pudimos procesar tu pago. Por favor, inténtalo de nuevo.</p>
		<?php else : ?>
			<p class="spirup-thankyou__text">Tu pedido será enviado a la dirección. ¡Gracias por tu compra!</p>
			<?php if ( $order ) : ?>
				<p class="spirup-thankyou__meta">N.º de pedido: <strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong></p>
			<?php endif; ?>
		<?php endif; ?>

		<a class="spirup-thankyou__btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Ir Inicio ›</a>
	</div>
</section>
<?php
// Hook estandar de WooCommerce (correos, integraciones, etc.).
do_action( 'woocommerce_thankyou', $order ? $order->get_id() : 0 );
