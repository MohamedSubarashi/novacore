<?php
/**
 * Archive Template
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
		if ( have_posts() ) :
			?>
			<header class="novacore-page-header">
				<?php
				the_archive_title( '<h1 class="novacore-page-title">', '</h1>' );
				the_archive_description( '<div class="novacore-archive-description">', '</div>' );
				?>
			</header>

			<div class="novacore-posts novacore-posts--archive" id="novacore-posts-container">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', get_post_type() );
				endwhile;
				?>
			</div>

			<?php
			novacore_pagination();

		else :
			get_template_part( 'template-parts/content/content', 'none' );
		endif;

		novacore_ad_area( 'after-content' );
		?>

		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
