<?php
/**
 * 404 Template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container">
	<div class="novacore-error-404">
		<div class="novacore-error-404__content">
			<span class="novacore-error-404__code">404</span>
			<h1 class="novacore-error-404__title">
				<?php esc_html_e( 'Page Not Found', 'novacore' ); ?>
			</h1>
			<p class="novacore-error-404__message">
				<?php esc_html_e( 'Sorry, the page you are looking for does not exist. It may have been moved, deleted, or the URL might be incorrect.', 'novacore' ); ?>
			</p>
			<div class="novacore-error-404__actions">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="novacore-btn novacore-btn--primary">
					<?php esc_html_e( 'Back to Home', 'novacore' ); ?>
				</a>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
