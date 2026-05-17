<?php
/**
 * WooCommerce Product Card Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'novacore-product-card', $product ); ?>>
	<?php
	do_action( 'novacore_before_product_card' );

	if ( $product->is_on_sale() ) {
		echo '<span class="novacore-product-card__badge novacore-product-card__badge--sale">' . esc_html__( 'Sale!', 'novacore' ) . '</span>';
	}
	?>
	<a href="<?php the_permalink(); ?>" class="novacore-product-card__link">
		<div class="novacore-product-card__image">
			<?php echo $product->get_image( 'woocommerce_thumbnail', [ 'loading' => 'lazy' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="novacore-product-card__body">
			<h3 class="novacore-product-card__title"><?php the_title(); ?></h3>
			<div class="novacore-product-card__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</a>
	<?php
	do_action( 'novacore_after_product_card' );
	?>
</li>
