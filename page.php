<?php
/**
 * Page Template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container">
	<div class="novacore-layout novacore-layout--page">
		<main id="main" class="novacore-main">

		<?php novacore_ad_area( 'before-content' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/page/content', 'page' );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;

		novacore_ad_area( 'after-content' );
		?>

		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
