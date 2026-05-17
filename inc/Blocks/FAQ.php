<?php
/**
 * NovaCore FAQ Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class FAQ {

	public function render( array $attributes ): string {
		$question = $attributes['question'] ?? '';
		$answer   = $attributes['answer'] ?? '';

		ob_start();
		?>
		<div class="novacore-faq__item">
			<button class="novacore-faq__question" type="button">
				<?php echo esc_html( $question ); ?>
				<span class="novacore-faq__icon"></span>
			</button>
			<div class="novacore-faq__answer">
				<p><?php echo esc_html( $answer ); ?></p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
