<?php
/**
 * NovaCore Newsletter Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Newsletter extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_newsletter',
			esc_html__( 'NovaCore Newsletter', 'novacore' ),
			[ 'description' => esc_html__( 'Email newsletter signup form.', 'novacore' ) ]
		);
	}

	public function widget( $args, $instance ): void {
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Newsletter', 'novacore' );
		$description = $instance['description'] ?? '';
		$placeholder = $instance['placeholder'] ?? esc_html__( 'Enter your email', 'novacore' );
		$button_text = $instance['button_text'] ?? esc_html__( 'Subscribe', 'novacore' );

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		?>
		<div class="novacore-widget-newsletter">
			<?php if ( $description ) : ?>
				<p class="novacore-widget-newsletter__text"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<form class="novacore-newsletter-form" action="#" method="post">
				<input type="email" name="email"
					placeholder="<?php echo esc_attr( $placeholder ); ?>" required />
				<button type="submit"><?php echo esc_html( $button_text ); ?></button>
			</form>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$title       = $instance['title'] ?? '';
		$description = $instance['description'] ?? '';
		$placeholder = $instance['placeholder'] ?? '';
		$button_text = $instance['button_text'] ?? '';
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
				<?php esc_html_e( 'Description:', 'novacore' ); ?>
			</label>
			<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>">
				<?php esc_html_e( 'Input Placeholder:', 'novacore' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" type="text"
				value="<?php echo esc_attr( $placeholder ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>">
				<?php esc_html_e( 'Button Text:', 'novacore' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text"
				value="<?php echo esc_attr( $button_text ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance                  = [];
		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['description']   = sanitize_textarea_field( $new_instance['description'] );
		$instance['placeholder']   = sanitize_text_field( $new_instance['placeholder'] );
		$instance['button_text']   = sanitize_text_field( $new_instance['button_text'] );
		return $instance;
	}
}
