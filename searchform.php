<?php
/**
 * Search form template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<form class="novacore-search-popup__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input class="novacore-search-popup__input" type="search"
		placeholder="<?php esc_attr_e( 'Search...', 'novacore' ); ?>"
		autocomplete="off"
		name="s" />
	<button class="novacore-search-popup__submit" type="submit">
		<?php esc_html_e( 'Search', 'novacore' ); ?>
	</button>
</form>
