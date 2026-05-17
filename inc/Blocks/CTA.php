<?php
/**
 * NovaCore CTA Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class CTA {

	public function render( array $attributes ): string {
		$title    = $attributes['title'] ?? '';
		$desc     = $attributes['description'] ?? '';
		$btn_text = $attributes['buttonText'] ?? '';
		$btn_url  = $attributes['buttonUrl'] ?? '#';

		ob_start();
		?>
		<div class="novacore-cta">
			<div class="novacore-container">
				<div class="novacore-cta__content">
					<?php if ( $title ) : ?>
						<h2 class="novacore-cta__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $desc ) : ?>
						<p class="novacore-cta__description"><?php echo esc_html( $desc ); ?></p>
					<?php endif; ?>
					<?php if ( $btn_text && $btn_url ) : ?>
						<a href="<?php echo esc_url( $btn_url ); ?>" class="novacore-btn novacore-btn--primary novacore-btn--lg">
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
