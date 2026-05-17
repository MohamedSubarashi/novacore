<?php
/**
 * Single Product Page
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="novacore-container">
	<?php
	while ( have_posts() ) :
		the_post();

		wc_get_template_part( 'content', 'single-product' );
	endwhile;
	?>
</div>

<?php
get_footer( 'shop' );
