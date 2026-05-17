<?php
/**
 * Related Posts Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$current_id = get_the_ID();
$categories = wp_get_post_categories( $current_id );
$tags       = wp_get_post_tags( $current_id, [ 'fields' => 'ids' ] );

$args = [
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'post__not_in'        => [ $current_id ],
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => true,
];

if ( ! empty( $categories ) ) {
	$args['category__in'] = $categories;
} elseif ( ! empty( $tags ) ) {
	$args['tag__in'] = $tags;
} else {
	return;
}

$related_query = new WP_Query( $args );

if ( ! $related_query->have_posts() ) {
	return;
}
?>

<div class="novacore-related">
	<h2 class="novacore-related__title">
		<?php esc_html_e( 'Related Posts', 'novacore' ); ?>
	</h2>
	<div class="novacore-related__grid">
		<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
			<article class="novacore-related__item">
				<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>" class="novacore-related__thumbnail">
					<?php the_post_thumbnail( 'novacore-sm', [ 'loading' => 'lazy' ] ); ?>
				</a>
				<?php endif; ?>
				<div class="novacore-related__body">
					<?php the_title( '<h3 class="novacore-related__post-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
					<div class="novacore-related__date">
						<?php echo esc_html( get_the_date() ); ?>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</div>

<?php
wp_reset_postdata();
