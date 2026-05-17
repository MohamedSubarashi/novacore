<?php
/**
 * Page Content Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-page-content' ); ?>>
	<header class="novacore-page-content__header">
		<?php the_title( '<h1 class="novacore-page-content__title">', '</h1>' ); ?>
	</header>
	<div class="novacore-page-content__body">
		<?php
		the_content();

		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'novacore' ),
			'after'  => '</div>',
		] );
		?>
	</div>
</article>
