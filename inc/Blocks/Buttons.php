<?php
/**
 * NovaCore Buttons Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Buttons {

	public function render( array $attributes ): string {
		$buttons = $attributes['buttons'] ?? [];
		$align   = $attributes['align'] ?? 'left';
		$gap     = absint( $attributes['gap'] ?? 12 );

		if ( empty( $buttons ) ) {
			return '';
		}

		$align_class = 'novacore-buttons--' . sanitize_html_class( $align );

		ob_start();
		?>
		<div class="novacore-buttons <?php echo esc_attr( $align_class ); ?>" style="gap:<?php echo esc_attr( $gap ); ?>px">
			<?php foreach ( $buttons as $button ) : ?>
				<?php
				$text  = $button['text'] ?? '';
				$url   = $button['url'] ?? '#';
				$style = ! empty( $button['style'] ) ? 'novacore-btn--' . sanitize_html_class( $button['style'] ) : 'novacore-btn--primary';
				$size  = ! empty( $button['size'] ) ? 'novacore-btn--' . sanitize_html_class( $button['size'] ) : 'novacore-btn--md';
				?>
				<?php if ( $text ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" class="novacore-btn <?php echo esc_attr( $style . ' ' . $size ); ?>">
						<?php echo esc_html( $text ); ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
