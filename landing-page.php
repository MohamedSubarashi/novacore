<?php
/**
 * Template Name: Landing Page
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container novacore-container--full">
	<main id="main" class="novacore-main novacore-main--full">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>
</div>

<?php
get_footer();
