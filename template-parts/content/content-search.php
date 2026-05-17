<?php
/**
 * Search Result Content Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-search-result' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a href="<?php the_permalink(); ?>" class="novacore-search-result__thumbnail">
		<?php the_post_thumbnail( 'novacore-sm', [ 'loading' => 'lazy' ] ); ?>
	</a>
	<?php endif; ?>

	<div class="novacore-search-result__body">
		<?php the_title( '<h2 class="novacore-search-result__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

		<div class="novacore-search-result__meta">
			<?php echo esc_html( get_the_date() ); ?>
		</div>

		<div class="novacore-search-result__excerpt">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
