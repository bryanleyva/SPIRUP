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
		<div class="spirup-footer__inner">
			<a class="spirup-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Spir Up - Inicio">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/imagenes/logo-white.png' ); ?>" alt="Spir Up">
			</a>

			<?php
			$spirup_priv  = get_page_by_path( 'politica-de-privacidad' );
			$spirup_priv_url = $spirup_priv ? get_permalink( $spirup_priv ) : '#';
			$spirup_terms = get_page_by_path( 'terminos-y-condiciones' );
			$spirup_terms_url = $spirup_terms ? get_permalink( $spirup_terms ) : '#';
			$spirup_lr = get_page_by_path( 'libro-de-reclamaciones' );
			$spirup_lr_url = $spirup_lr ? get_permalink( $spirup_lr ) : '#';
			?>
			<nav class="spirup-footer__links" aria-label="Enlaces legales">
				<a href="<?php echo esc_url( $spirup_lr_url ); ?>">Libros de reclamaciones</a>
				<a href="<?php echo esc_url( $spirup_priv_url ); ?>">Política de privacidad</a>
				<a href="<?php echo esc_url( $spirup_terms_url ); ?>">Términos y condiciones</a>
			</nav>

			<div class="spirup-footer__social">
				<a href="#" aria-label="Facebook">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.6c-.29-.04-1.28-.13-2.43-.13-2.4 0-4.05 1.47-4.05 4.16V9.9H7.5V13h2.72v8z"/></svg>
				</a>
				<a href="#" aria-label="TikTok">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.5 3c.32 2.03 1.5 3.42 3.5 3.6V9.1c-1.28 0-2.48-.4-3.5-1.1v6.35c0 3.2-2.42 5.65-5.5 5.65S6 17.55 6 14.35s2.42-5.65 5.5-5.65c.3 0 .6.02.9.07v2.66a3 3 0 0 0-.9-.14c-1.6 0-2.9 1.4-2.9 3.06s1.3 3.06 2.9 3.06 2.9-1.37 2.9-3.06V3z"/></svg>
				</a>
				<a href="#" aria-label="Instagram">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5.2"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>
				</a>
			</div>
		</div>
	</footer>

</div><!-- .spirup-site -->

<?php wp_footer(); ?>
</body>
</html>
