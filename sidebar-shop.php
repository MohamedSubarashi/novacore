<?php
/**
 * Shop Sidebar
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-shop' ) ) {
	return;
}
?>

<aside id="secondary" class="novacore-sidebar" role="complementary"
	aria-label="<?php esc_attr_e( 'Shop Sidebar', 'novacore' ); ?>">
	<?php dynamic_sidebar( 'sidebar-shop' ); ?>
</aside>
