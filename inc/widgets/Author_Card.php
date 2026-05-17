<?php
/**
 * NovaCore Author Card Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Author_Card extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_author_card',
			esc_html__( 'NovaCore Author Card', 'novacore' ),
			[ 'description' => esc_html__( 'Display author profile card.', 'novacore' ) ]
		);
	}

	public function widget( $args, $instance ): void {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$user_id = ! empty( $instance['user_id'] ) ? absint( $instance['user_id'] ) : get_current_user_id();
		$user    = get_userdata( $user_id );

		if ( ! $user ) {
			return;
		}

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}
		?>
		<div class="novacore-author-card">
			<div class="novacore-author-card__avatar">
				<?php echo get_avatar( $user_id, 80, '', $user->display_name ); ?>
			</div>
			<h4 class="novacore-author-card__name">
				<?php echo esc_html( $user->display_name ); ?>
			</h4>
			<?php if ( ! empty( $user->description ) ) : ?>
				<p class="novacore-author-card__bio">
					<?php echo esc_html( wp_trim_words( $user->description, 20 ) ); ?>
				</p>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>"
				class="novacore-author-card__link">
				<?php esc_html_e( 'View All Posts', 'novacore' ); ?> &rarr;
			</a>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$title   = $instance['title'] ?? '';
		$user_id = $instance['user_id'] ?? '';
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>">
				<?php esc_html_e( 'User ID:', 'novacore' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'user_id' ) ); ?>" type="number"
				value="<?php echo esc_attr( (string) $user_id ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance              = [];
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['user_id']   = absint( $new_instance['user_id'] );
		return $instance;
	}
}
