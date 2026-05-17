<?php
/**
 * NovaCore Counter Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Counter {

	public function render( array $attributes ): string {
		$number = $attributes['number'] ?? '0';
		$suffix = $attributes['suffix'] ?? '';
		$label  = $attributes['label'] ?? '';

		ob_start();
		?>
		<div class="novacore-counter">
			<div class="novacore-counter__number" data-count="<?php echo esc_attr( $number ); ?>">
				<span class="novacore-counter__value">0</span>
				<?php if ( $suffix ) : ?>
					<span class="novacore-counter__suffix"><?php echo esc_html( $suffix ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $label ) : ?>
				<div class="novacore-counter__label"><?php echo esc_html( $label ); ?></div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
