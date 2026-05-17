<?php
/**
 * Default Content Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a href="<?php the_permalink(); ?>" class="novacore-card__thumbnail" aria-hidden="true" tabindex="-1">
		<?php the_post_thumbnail( 'novacore-md', [ 'loading' => 'lazy' ] ); ?>
		<?php
		$categories = get_the_category();
		if ( ! empty( $categories ) ) :
			?>
		<span class="novacore-card__cat-badge"><?php echo esc_html( $categories[0]->name ); ?></span>
		<?php endif; ?>
	</a>
	<?php endif; ?>

	<div class="novacore-card__body">
		<?php the_title( '<h2 class="novacore-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

		<div class="novacore-card__meta">
			<span class="novacore-card__date">
				<?php echo esc_html( get_the_date() ); ?>
			</span>
			<span class="novacore-card__reading-time">
				<?php echo esc_html( novacore_reading_time() ); ?>
			</span>
		</div>

		<div class="novacore-card__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<?php if ( function_exists( 'novacore_share_buttons' ) ) : ?>
			<?php novacore_share_buttons( 'card' ); ?>
		<?php endif; ?>

		<a href="<?php the_permalink(); ?>" class="novacore-card__read-more">
			<?php esc_html_e( 'Read More', 'novacore' ); ?>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<line x1="5" y1="12" x2="19" y2="12"></line>
				<polyline points="12 5 19 12 12 19"></polyline>
			</svg>
		</a>
	</div>
</article>
