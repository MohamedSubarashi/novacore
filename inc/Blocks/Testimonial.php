<?php
/**
 * NovaCore Testimonial Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Testimonial {

	public function render( array $attributes ): string {
		$text   = $attributes['text'] ?? '';
		$author = $attributes['authorName'] ?? '';
		$role   = $attributes['authorRole'] ?? '';
		$rating = $attributes['rating'] ?? 5;

		ob_start();
		?>
		<div class="novacore-testimonial novacore-testimonial--card">
			<div class="novacore-testimonial__stars">
				<?php for ( $i = 0; $i < min( (int) $rating, 5 ); $i++ ) : ?>
					<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
				<?php endfor; ?>
			</div>
			<?php if ( $text ) : ?>
				<p class="novacore-testimonial__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<div class="novacore-testimonial__author">
				<div class="novacore-testimonial__author-info">
					<?php if ( $author ) : ?>
						<div class="novacore-testimonial__author-name"><?php echo esc_html( $author ); ?></div>
					<?php endif; ?>
					<?php if ( $role ) : ?>
						<div class="novacore-testimonial__author-role"><?php echo esc_html( $role ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
