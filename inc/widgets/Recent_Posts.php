<?php
/**
 * NovaCore Recent Posts Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Recent_Posts extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_recent_posts',
			esc_html__( 'NovaCore Recent Posts', 'novacore' ),
			[ 'description' => esc_html__( 'Recent posts with thumbnails.', 'novacore' ) ]
		);
	}

	public function widget( $args, $instance ): void {
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'novacore' );
		$number    = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = ! empty( $instance['show_date'] );

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html( $title ) . $args['after_title'];

		$posts = get_posts( [
			'posts_per_page'      => $number,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		] );

		if ( ! empty( $posts ) ) {
			echo '<ul class="novacore-widget-post-list">';
			foreach ( $posts as $post ) {
				setup_postdata( $post );
				?>
				<li>
					<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
							<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail', [ 'loading' => 'lazy' ] ); ?>
						</a>
					<?php endif; ?>
					<div class="novacore-widget-post__info">
						<h4 class="novacore-widget-post__title">
							<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
								<?php echo esc_html( get_the_title( $post->ID ) ); ?>
							</a>
						</h4>
						<?php if ( $show_date ) : ?>
							<span class="novacore-widget-post__date">
								<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
							</span>
						<?php endif; ?>
					</div>
				</li>
				<?php
			}
			wp_reset_postdata();
			echo '</ul>';
		}

		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$title     = $instance['title'] ?? '';
		$number    = $instance['number'] ?? 5;
		$show_date = ! empty( $instance['show_date'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'novacore' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
				<?php esc_html_e( 'Number of posts:', 'novacore' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number"
				step="1" min="1" value="<?php echo esc_attr( (string) $number ); ?>" size="3">
		</p>
		<p>
			<input class="checkbox" type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>"
				<?php checked( $show_date ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<?php esc_html_e( 'Display date?', 'novacore' ); ?>
			</label>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance              = [];
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['number']    = absint( $new_instance['number'] );
		$instance['show_date'] = ! empty( $new_instance['show_date'] );
		return $instance;
	}
}
