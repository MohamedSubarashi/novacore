<?php
/**
 * Blog Grid Layout Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a class="novacore-card__thumbnail" href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail( 'novacore-md', [ 'loading' => 'lazy' ] ); ?>
	</a>
	<?php endif; ?>
	<div class="novacore-card__body">
		<?php
		$categories = get_the_category();
		if ( ! empty( $categories ) ) :
			?>
		<div class="novacore-card__categories">
			<a class="novacore-card__cat" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
				<?php echo esc_html( $categories[0]->name ); ?>
			</a>
		</div>
		<?php endif; ?>
		<h3 class="novacore-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="novacore-card__meta">
			<span class="novacore-card__date"><?php echo esc_html( get_the_date() ); ?></span>
			<span class="novacore-card__reading-time"><?php echo esc_html( novacore_reading_time() ); ?></span>
		</div>
		<div class="novacore-card__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<a class="novacore-card__read-more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read More', 'novacore' ); ?>
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
		</a>
	</div>
</article>
