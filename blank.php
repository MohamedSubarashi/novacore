<?php
/**
 * Template Name: Blank Canvas
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'blank' );
?>

<main id="main" class="novacore-main novacore-main--blank">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer( 'blank' );
