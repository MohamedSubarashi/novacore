<?php
/**
 * NovaCore Ad Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Ad extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_ad',
			esc_html__( 'NovaCore Ad', 'novacore' ),
			[
				'description' => esc_html__( 'Display an advertisement (image, code, or AdSense).', 'novacore' ),
			]
		);
	}

	public function widget( $args, $instance ): void {
		$hide_classes = $this->get_visibility_classes( $instance );

		if ( ! empty( $instance['widget_class'] ) ) {
			$args['before_widget'] = str_replace(
				'class="',
				'class="' . esc_attr( $instance['widget_class'] ) . ' ',
				$args['before_widget']
			);
		}

		echo str_replace(
			'class="',
			'class="' . $hide_classes . ' ',
			$args['before_widget']
		);

		if ( ! empty( $instance['show_label'] ) ) {
			echo '<span class="novacore-ad-widget__label">' . esc_html__( 'Advertisement', 'novacore' ) . '</span>';
		}

		$type = ! empty( $instance['type'] ) ? $instance['type'] : 'image';

		if ( 'code' === $type && ! empty( $instance['code'] ) ) {
			echo '<div class="novacore-ad novacore-ad--code">';
			echo $instance['code']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		} elseif ( 'image' === $type && ! empty( $instance['image_url'] ) ) {
			$link_url  = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '';
			$alt_text  = ! empty( $instance['alt_text'] ) ? $instance['alt_text'] : 'Advertisement';
			$nofollow  = ! empty( $instance['nofollow'] );
			$rel_attr  = 'noopener noreferrer' . ( $nofollow ? ' nofollow' : '' );
			$output    = '<div class="novacore-ad novacore-ad--image">';
			if ( $link_url ) {
				$output .= '<a href="' . esc_url( $link_url ) . '" target="_blank" rel="' . esc_attr( $rel_attr ) . '">';
			}
			$output .= '<img src="' . esc_url( $instance['image_url'] ) . '" alt="' . esc_attr( $alt_text ) . '" loading="lazy">';
			if ( $link_url ) {
				$output .= '</a>';
			}
			$output .= '</div>';
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo $args['after_widget'];
	}

	private function get_visibility_classes( array $instance ): string {
		$classes = [];

		if ( ! empty( $instance['hide_mobile'] ) ) {
			$classes[] = 'novacore-hide-mobile';
		}
		if ( ! empty( $instance['hide_tablet'] ) ) {
			$classes[] = 'novacore-hide-tablet';
		}
		if ( ! empty( $instance['hide_desktop'] ) ) {
			$classes[] = 'novacore-hide-desktop';
		}

		return implode( ' ', $classes );
	}

	public function form( $instance ): void {
		$type       = ! empty( $instance['type'] ) ? $instance['type'] : 'image';
		$image_url  = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
		$link_url   = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '';
		$alt_text   = ! empty( $instance['alt_text'] ) ? $instance['alt_text'] : '';
		$code       = ! empty( $instance['code'] ) ? $instance['code'] : '';
		$show_label = ! empty( $instance['show_label'] );
		$nofollow   = ! empty( $instance['nofollow'] );
		$hide_mobile  = ! empty( $instance['hide_mobile'] );
		$hide_tablet  = ! empty( $instance['hide_tablet'] );
		$hide_desktop = ! empty( $instance['hide_desktop'] );
		$widget_class = ! empty( $instance['widget_class'] ) ? $instance['widget_class'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Ad Type:', 'novacore' ); ?></label>
			<select class="novacore-ad-type-select" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
				<option value="image" <?php selected( $type, 'image' ); ?>><?php esc_html_e( 'Image Ad', 'novacore' ); ?></option>
				<option value="code" <?php selected( $type, 'code' ); ?>><?php esc_html_e( 'Custom Code / AdSense', 'novacore' ); ?></option>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_label' ) ); ?>" type="checkbox" value="1" <?php checked( $show_label ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_label' ) ); ?>"><?php esc_html_e( 'Show "Advertisement" label', 'novacore' ); ?></label>
		</p>
		<p class="novacore-ad-fields-image" <?php echo 'code' === $type ? 'style="display:none"' : ''; ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_html_e( 'Image URL:', 'novacore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="url" value="<?php echo esc_url( $image_url ); ?>">
		</p>
		<p class="novacore-ad-fields-image" <?php echo 'code' === $type ? 'style="display:none"' : ''; ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>"><?php esc_html_e( 'Link URL:', 'novacore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="url" value="<?php echo esc_url( $link_url ); ?>">
		</p>
		<p class="novacore-ad-fields-image" <?php echo 'code' === $type ? 'style="display:none"' : ''; ?>>
			<input id="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nofollow' ) ); ?>" type="checkbox" value="1" <?php checked( $nofollow ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"><?php esc_html_e( 'Add nofollow to link', 'novacore' ); ?></label>
		</p>
		<p class="novacore-ad-fields-image" <?php echo 'code' === $type ? 'style="display:none"' : ''; ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>"><?php esc_html_e( 'Alt Text:', 'novacore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alt_text' ) ); ?>" type="text" value="<?php echo esc_attr( $alt_text ); ?>">
		</p>
		<p class="novacore-ad-fields-code" <?php echo 'image' === $type ? 'style="display:none"' : ''; ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>"><?php esc_html_e( 'Ad Code / AdSense:', 'novacore' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'code' ) ); ?>" rows="6"><?php echo esc_textarea( $code ); ?></textarea>
		</p>
		<fieldset style="border:1px solid #ddd;padding:10px;margin-top:12px;">
			<legend style="padding:0 6px;font-weight:600;"><?php esc_html_e( 'Visibility', 'novacore' ); ?></legend>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'hide_mobile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_mobile' ) ); ?>" type="checkbox" value="1" <?php checked( $hide_mobile ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'hide_mobile' ) ); ?>"><?php esc_html_e( 'Hide on mobile', 'novacore' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'hide_tablet' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_tablet' ) ); ?>" type="checkbox" value="1" <?php checked( $hide_tablet ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'hide_tablet' ) ); ?>"><?php esc_html_e( 'Hide on tablet', 'novacore' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'hide_desktop' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_desktop' ) ); ?>" type="checkbox" value="1" <?php checked( $hide_desktop ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'hide_desktop' ) ); ?>"><?php esc_html_e( 'Hide on desktop', 'novacore' ); ?></label>
			</p>
		</fieldset>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_class' ) ); ?>"><?php esc_html_e( 'Additional CSS Class:', 'novacore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_class' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_class ); ?>">
		</p>
		<script>
		jQuery(document).ready(function($) {
			$('select.novacore-ad-type-select').on('change', function() {
				var val = $(this).val();
				$(this).closest('.widget-content').find('.novacore-ad-fields-image').toggle(val === 'image');
				$(this).closest('.widget-content').find('.novacore-ad-fields-code').toggle(val === 'code');
			});
		});
		</script>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance                = [];
		$instance['type']        = ! empty( $new_instance['type'] ) ? sanitize_text_field( $new_instance['type'] ) : 'image';
		$instance['image_url']   = ! empty( $new_instance['image_url'] ) ? esc_url_raw( $new_instance['image_url'] ) : '';
		$instance['link_url']    = ! empty( $new_instance['link_url'] ) ? esc_url_raw( $new_instance['link_url'] ) : '';
		$instance['alt_text']    = ! empty( $new_instance['alt_text'] ) ? sanitize_text_field( $new_instance['alt_text'] ) : '';
		$instance['show_label']  = ! empty( $new_instance['show_label'] );
		$instance['nofollow']    = ! empty( $new_instance['nofollow'] );
		$instance['hide_mobile']  = ! empty( $new_instance['hide_mobile'] );
		$instance['hide_tablet']  = ! empty( $new_instance['hide_tablet'] );
		$instance['hide_desktop'] = ! empty( $new_instance['hide_desktop'] );
		$instance['widget_class'] = ! empty( $new_instance['widget_class'] ) ? sanitize_html_class( $new_instance['widget_class'] ) : '';
		if ( ! empty( $new_instance['code'] ) ) {
			$instance['code'] = wp_kses_post( $new_instance['code'] );
		} else {
			$instance['code'] = '';
		}
		return $instance;
	}
}
