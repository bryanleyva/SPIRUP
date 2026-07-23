<?php
/**
 * Footer personalizado de SPIRUP.
 *
 * Minimo por ahora: cierra la estructura y llama a wp_footer().
 * El pie de pagina real se construira en una parte posterior.
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	<footer class="spirup-footer">
		<div class="spirup-container">
			<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> Spir Up · FR Soluciones</p>
		</div>
	</footer>

</div><!-- .spirup-site -->

<?php wp_footer(); ?>
</body>
</html>
