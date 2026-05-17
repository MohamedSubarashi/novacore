<?php
/**
 * NovaCore Post Slider Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Post_Slider extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_post_slider',
			esc_html__( 'NovaCore Post Slider', 'novacore' ),
			[
				'description' => esc_html__( 'A slideshow of your latest posts.', 'novacore' ),
			]
		);
	}

	public function widget( $args, $instance ): void {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$number  = min( max( (int) ( $instance['number'] ?? 5 ), 2 ), 20 );
		$orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
		$order   = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';
		$category = ! empty( $instance['category'] ) ? (int) $instance['category'] : 0;
		$autoplay = ! empty( $instance['autoplay'] );
		$show_excerpt = ! empty( $instance['show_excerpt'] );
		$show_date    = ! empty( $instance['show_date'] );
		$show_category = ! empty( $instance['show_category'] );
		$slide_height = min( max( (int) ( $instance['slide_height'] ?? 400 ), 200 ), 700 );

		$query_args = [
			'posts_per_page'      => $number,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => $orderby,
			'order'               => $order,
		];

		if ( $category > 0 ) {
			$query_args['cat'] = $category;
		}

		$posts = get_posts( $query_args );

		if ( empty( $posts ) ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$slider_id = 'novacore-ps-' . $this->id;
		$wrapper_class = 'novacore-slider novacore-post-slider';
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>"
			id="<?php echo esc_attr( $slider_id ); ?>"
			data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>"
			data-autoplay-speed="5000">
			<div class="novacore-slider__track">
				<?php foreach ( $posts as $i => $post ) : ?>
					<?php setup_postdata( $post ); ?>
					<div class="novacore-slider__slide novacore-post-slider__slide<?php echo $i === 0 ? ' is-active' : ''; ?>"
						data-index="<?php echo esc_attr( $i ); ?>">
						<article class="novacore-post-slider__article" style="height:<?php echo esc_attr( $slide_height ); ?>px">
							<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
							<div class="novacore-post-slider__bg">
								<?php echo get_the_post_thumbnail( $post->ID, 'novacore-xl', [ 'loading' => $i === 0 ? 'eager' : 'lazy' ] ); ?>
							</div>
							<?php endif; ?>
							<div class="novacore-post-slider__overlay">
								<div class="novacore-post-slider__content">
									<?php if ( $show_category ) : ?>
										<?php
										$cats = get_the_category( $post->ID );
										if ( ! empty( $cats ) ) :
											?>
										<span class="novacore-post-slider__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
										<?php endif; ?>
									<?php endif; ?>
									<h3 class="novacore-post-slider__title">
										<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
											<?php echo esc_html( get_the_title( $post->ID ) ); ?>
										</a>
									</h3>
									<?php if ( $show_date ) : ?>
										<span class="novacore-post-slider__date"><?php echo esc_html( get_the_date( '', $post->ID ) ); ?></span>
									<?php endif; ?>
									<?php if ( $show_excerpt ) : ?>
										<p class="novacore-post-slider__excerpt">
											<?php echo esc_html( wp_trim_words( get_the_excerpt( $post->ID ), 20 ) ); ?>
										</p>
									<?php endif; ?>
								</div>
							</div>
						</article>
					</div>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<?php if ( count( $posts ) > 1 ) : ?>
			<button class="novacore-slider__arrow novacore-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'novacore' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<button class="novacore-slider__arrow novacore-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'novacore' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
			<div class="novacore-slider__dots" role="tablist">
				<?php foreach ( $posts as $index => $post ) : ?>
					<button class="novacore-slider__dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
						data-slide="<?php echo esc_attr( $index ); ?>"
						role="tab"
						aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'novacore' ), $index + 1 ) ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$title     = $instance['title'] ?? '';
		$number    = $instance['number'] ?? 5;
		$orderby   = $instance['orderby'] ?? 'date';
		$order     = $instance['order'] ?? 'DESC';
		$category  = $instance['category'] ?? 0;
		$autoplay  = ! empty( $instance['autoplay'] );
		$show_excerpt  = ! empty( $instance['show_excerpt'] );
		$show_date     = ! empty( $instance['show_date'] );
		$show_category = ! empty( $instance['show_category'] );
		$slide_height  = $instance['slide_height'] ?? 400;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'novacore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'novacore' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="2" max="20" value="<?php echo esc_attr( (string) $number ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order by:', 'novacore' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
				<option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Date', 'novacore' ); ?></option>
				<option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Title', 'novacore' ); ?></option>
				<option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php esc_html_e( 'Random', 'novacore' ); ?></option>
				<option value="comment_count" <?php selected( $orderby, 'comment_count' ); ?>><?php esc_html_e( 'Popular', 'novacore' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'novacore' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'novacore' ); ?></option>
				<option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'novacore' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'novacore' ); ?></label>
			<?php
			wp_dropdown_categories( [
				'show_option_all' => esc_html__( 'All Categories', 'novacore' ),
				'hide_empty'      => 0,
				'name'            => $this->get_field_name( 'category' ),
				'id'              => $this->get_field_id( 'category' ),
				'selected'        => $category,
				'class'           => 'widefat',
			] );
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slide_height' ) ); ?>"><?php esc_html_e( 'Slide height (px):', 'novacore' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'slide_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slide_height' ) ); ?>" type="number" step="10" min="200" max="700" value="<?php echo esc_attr( (string) $slide_height ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>" <?php checked( $autoplay ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"><?php esc_html_e( 'Enable autoplay', 'novacore' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" <?php checked( $show_category ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>"><?php esc_html_e( 'Show category', 'novacore' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" <?php checked( $show_date ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Show date', 'novacore' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" <?php checked( $show_excerpt ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Show excerpt', 'novacore' ); ?></label>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance = [];
		$instance['title']    = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['number']   = min( max( (int) ( $new_instance['number'] ?? 5 ), 2 ), 20 );
		$instance['orderby']  = in_array( $new_instance['orderby'] ?? '', [ 'date', 'title', 'rand', 'comment_count' ] ) ? $new_instance['orderby'] : 'date';
		$instance['order']    = in_array( $new_instance['order'] ?? '', [ 'ASC', 'DESC' ] ) ? $new_instance['order'] : 'DESC';
		$instance['category'] = (int) ( $new_instance['category'] ?? 0 );
		$instance['autoplay']  = ! empty( $new_instance['autoplay'] );
		$instance['show_excerpt']  = ! empty( $new_instance['show_excerpt'] );
		$instance['show_date']     = ! empty( $new_instance['show_date'] );
		$instance['show_category'] = ! empty( $new_instance['show_category'] );
		$instance['slide_height']  = min( max( (int) ( $new_instance['slide_height'] ?? 400 ), 200 ), 700 );
		return $instance;
	}
}
