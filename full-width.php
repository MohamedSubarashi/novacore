<?php
/**
 * Template Name: Full Width
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container novacore-container--wide">
	<main id="main" class="novacore-main novacore-main--no-sidebar">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content/content', 'page' );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile;
		?>
	</main>
</div>

<?php
get_footer();
