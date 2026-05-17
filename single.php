<?php
/**
 * Single Post Template
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

		<?php novacore_ad_area( 'before-content' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', 'single' );

			if ( get_theme_mod( 'novacore_show_author_box', true ) ) {
				get_template_part( 'template-parts/content/content', 'author' );
			}

			if ( get_theme_mod( 'novacore_show_related_posts', true ) ) {
				get_template_part( 'template-parts/content/content', 'related' );
			}

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
