<?php
/**
 * WooCommerce Template Override
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container">
	<div class="novacore-layout">
		<main id="main" class="novacore-main">
			<?php woocommerce_content(); ?>
		</main>
		<?php get_sidebar( 'shop' ); ?>
	</div>
</div>

<?php
get_footer();
