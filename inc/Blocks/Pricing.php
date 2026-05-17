<?php
/**
 * NovaCore Pricing Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Pricing {

	public function render( array $attributes ): string {
		$name     = $attributes['planName'] ?? 'Basic';
		$price    = $attributes['price'] ?? '$19';
		$currency = $attributes['currency'] ?? '$';
		$period   = $attributes['period'] ?? '/month';
		$desc     = $attributes['description'] ?? '';
		$features = $attributes['features'] ?? '';
		$btn_text = $attributes['buttonText'] ?? 'Choose Plan';
		$btn_url  = $attributes['buttonUrl'] ?? '#';
		$featured = $attributes['featured'] ?? false;

		$class = 'novacore-pricing';
		if ( $featured ) {
			$class .= ' novacore-pricing--featured';
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php if ( $featured ) : ?>
				<span class="novacore-pricing__badge"><?php esc_html_e( 'Popular', 'novacore' ); ?></span>
			<?php endif; ?>
			<div class="novacore-pricing__header">
				<h3 class="novacore-pricing__name"><?php echo esc_html( $name ); ?></h3>
				<?php if ( $desc ) : ?>
					<p class="novacore-pricing__description"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
			<div class="novacore-pricing__price">
				<span class="novacore-pricing__price-currency"><?php echo esc_html( $currency ); ?></span>
				<span class="novacore-pricing__price-amount"><?php echo esc_html( $price ); ?></span>
				<span class="novacore-pricing__price-period"><?php echo esc_html( $period ); ?></span>
			</div>
			<?php if ( $features ) : ?>
				<ul class="novacore-pricing__features">
					<?php
					$lines = explode( "\n", $features );
					foreach ( $lines as $line ) :
						$line = trim( $line );
						if ( empty( $line ) ) continue;
						$disabled = str_starts_with( $line, '-' );
						$line = ltrim( $line, '-' );
						?>
						<li class="<?php echo $disabled ? 'is-disabled' : ''; ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php echo esc_html( $line ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="novacore-pricing__action">
				<a href="<?php echo esc_url( $btn_url ); ?>" class="novacore-btn novacore-btn--primary">
					<?php echo esc_html( $btn_text ); ?>
				</a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
