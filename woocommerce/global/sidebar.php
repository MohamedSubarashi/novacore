<?php
defined( 'ABSPATH' ) || exit;
if ( ! is_active_sidebar( 'sidebar-shop' ) ) return;
?>
<aside class="novacore-sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-shop' ); ?>
</aside>
