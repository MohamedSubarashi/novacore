<?php
/**
 * No Content Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="novacore-no-results">
	<?php if ( is_search() ) : ?>
		<h2 class="novacore-no-results__title">
			<?php esc_html_e( 'Nothing Found', 'novacore' ); ?>
		</h2>
		<p class="novacore-no-results__message">
			<?php esc_html_e( 'Sorry, no results were found for your search. Please try different keywords.', 'novacore' ); ?>
		</p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<h2 class="novacore-no-results__title">
			<?php esc_html_e( 'Nothing Found', 'novacore' ); ?>
		</h2>
		<p class="novacore-no-results__message">
			<?php esc_html_e( 'It seems we cannot find what you are looking for. Try searching.', 'novacore' ); ?>
		</p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
