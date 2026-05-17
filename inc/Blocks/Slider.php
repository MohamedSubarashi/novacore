<?php
/**
 * NovaCore Slider Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Slider {

	public function render( array $attributes ): string {
		$images       = $attributes['images'] ?? '';
		$slide_height = min( max( (int) ( $attributes['slideHeight'] ?? 500 ), 200 ), 900 );
		$autoplay     = ! empty( $attributes['autoplay'] );
		$autoplay_speed = max( (int) ( $attributes['autoplaySpeed'] ?? 5000 ), 1000 );
		$show_dots    = ! empty( $attributes['showDots'] );
		$show_arrows  = ! empty( $attributes['showArrows'] );

		$image_ids = array_filter( array_map( 'intval', explode( ',', $images ) ) );

		if ( empty( $image_ids ) ) {
			return '';
		}

		$wrapper_class = 'novacore-slider';
		if ( $autoplay ) {
			$wrapper_class .= ' novacore-slider--autoplay';
		}

		$slider_id = 'novacore-slider-' . wp_rand( 1000, 9999 );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>"
			id="<?php echo esc_attr( $slider_id ); ?>"
			data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>"
			data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>">
			<div class="novacore-slider__track">
				<?php
				$i = 0;
				foreach ( $image_ids as $image_id ) :
					$image_url  = wp_get_attachment_image_url( $image_id, 'novacore-xl' );
					$image_alt  = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					$image_srcset = wp_get_attachment_image_srcset( $image_id, 'novacore-xl' );
					if ( ! $image_url ) {
						continue;
					}
					$caption = wp_get_attachment_caption( $image_id );
					?>
					<div class="novacore-slider__slide<?php echo $i === 0 ? ' is-active' : ''; ?>"
						data-index="<?php echo esc_attr( $i ); ?>">
						<figure class="novacore-slider__figure" style="height:<?php echo esc_attr( $slide_height ); ?>px">
							<img src="<?php echo esc_url( $image_url ); ?>"
								srcset="<?php echo esc_attr( $image_srcset ); ?>"
								sizes="(max-width: 1200px) 100vw, 1200px"
								alt="<?php echo esc_attr( $image_alt ?: '' ); ?>"
								loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>">
							<?php if ( $caption ) : ?>
								<figcaption class="novacore-slider__caption"><?php echo esc_html( $caption ); ?></figcaption>
							<?php endif; ?>
						</figure>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $show_arrows && count( $image_ids ) > 1 ) : ?>
			<button class="novacore-slider__arrow novacore-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'novacore' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<button class="novacore-slider__arrow novacore-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'novacore' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
			<?php endif; ?>

			<?php if ( $show_dots && count( $image_ids ) > 1 ) : ?>
			<div class="novacore-slider__dots" role="tablist">
				<?php foreach ( $image_ids as $index => $id ) : ?>
					<button class="novacore-slider__dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
						data-slide="<?php echo esc_attr( $index ); ?>"
						role="tab"
						aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'novacore' ), $index + 1 ) ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
