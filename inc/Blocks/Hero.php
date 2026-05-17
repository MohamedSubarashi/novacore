<?php
/**
 * NovaCore Hero Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Hero {

	public function render( array $attributes ): string {
		$title    = $attributes['title'] ?? 'Welcome to NovaCore';
		$desc     = $attributes['description'] ?? '';
		$btn_text = $attributes['buttonText'] ?? '';
		$btn_url  = $attributes['buttonUrl'] ?? '';
		$btn2_text = $attributes['secondaryButtonText'] ?? '';
		$btn2_url  = $attributes['secondaryButtonUrl'] ?? '';
		$align    = $attributes['alignment'] ?? 'left';
		$style    = $attributes['style'] ?? 'default';
		$image    = $attributes['showImage'] && ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';

		$wrapper_class = 'novacore-hero';
		if ( 'center' === $align ) {
			$wrapper_class .= ' novacore-hero--center';
		}
		if ( $image ) {
			$wrapper_class .= ' novacore-hero--split';
		}
		if ( 'glass' === $style ) {
			$wrapper_class .= ' novacore-hero--glass';
		}

		ob_start();
		?>
		<section class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="novacore-hero__bg">
				<div class="gradient-orb"></div>
				<div class="gradient-orb"></div>
			</div>
			<div class="novacore-hero__container">
				<div class="novacore-hero__content">
					<?php if ( $title ) : ?>
						<h1 class="novacore-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
					<?php endif; ?>
					<?php if ( $desc ) : ?>
						<p class="novacore-hero__description"><?php echo esc_html( $desc ); ?></p>
					<?php endif; ?>
					<div class="novacore-hero__actions">
						<?php if ( $btn_text && $btn_url ) : ?>
							<a href="<?php echo esc_url( $btn_url ); ?>" class="novacore-btn novacore-btn--primary novacore-btn--lg">
								<?php echo esc_html( $btn_text ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $btn2_text && $btn2_url ) : ?>
							<a href="<?php echo esc_url( $btn2_url ); ?>" class="novacore-btn novacore-btn--secondary novacore-btn--lg">
								<?php echo esc_html( $btn2_text ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $image ) : ?>
					<div class="novacore-hero__image">
						<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
