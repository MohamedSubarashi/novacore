<?php
/**
 * NovaCore Post Grid Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Post_Grid {

	public function render( array $attributes ): string {
		$title        = $attributes['title'] ?? '';
		$columns      = min( max( (int) ( $attributes['columns'] ?? 3 ), 1 ), 4 );
		$per_page     = min( max( (int) ( $attributes['postsPerPage'] ?? 6 ), 1 ), 24 );
		$category     = $attributes['category'] ?? '';
		$order_by     = in_array( $attributes['orderBy'] ?? '', [ 'date', 'title', 'rand' ], true ) ? $attributes['orderBy'] : 'date';
		$order        = in_array( $attributes['order'] ?? '', [ 'asc', 'desc' ], true ) ? strtoupper( $attributes['order'] ) : 'DESC';
		$show_excerpt = ! empty( $attributes['showExcerpt'] );
		$show_date    = ! empty( $attributes['showDate'] );
		$show_thumb   = ! empty( $attributes['showThumbnail'] );
		$show_author  = ! empty( $attributes['showAuthor'] );

		$args = [
			'post_type'      => 'post',
			'posts_per_page' => $per_page,
			'orderby'        => $order_by,
			'order'          => $order,
			'ignore_sticky_posts' => true,
		];

		if ( ! empty( $category ) ) {
			$args['category_name'] = sanitize_title( $category );
		}

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return '<p>' . esc_html__( 'No posts found.', 'novacore' ) . '</p>';
		}

		$wrapper_class = 'novacore-post-grid';
		$wrapper_class .= " novacore-post-grid--cols-{$columns}";

		ob_start();
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php if ( $title ) : ?>
				<div class="novacore-post-grid__header">
					<h2 class="novacore-post-grid__title"><?php echo esc_html( $title ); ?></h2>
				</div>
			<?php endif; ?>
			<div class="novacore-post-grid__grid">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-post-grid__card' ); ?>>
						<?php if ( $show_thumb && has_post_thumbnail() ) : ?>
							<a class="novacore-post-grid__thumbnail" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'novacore-md' ); ?>
							</a>
						<?php endif; ?>
						<div class="novacore-post-grid__body">
							<h3 class="novacore-post-grid__post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php if ( $show_date || $show_author ) : ?>
								<div class="novacore-post-grid__meta">
									<?php if ( $show_date ) : ?>
										<span class="novacore-post-grid__date">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
											<?php echo get_the_date(); ?>
										</span>
									<?php endif; ?>
									<?php if ( $show_author ) : ?>
										<span class="novacore-post-grid__author">
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
											<?php the_author(); ?>
										</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if ( $show_excerpt ) : ?>
								<div class="novacore-post-grid__excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>
							<a class="novacore-post-grid__read-more" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'Read More', 'novacore' ); ?>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
