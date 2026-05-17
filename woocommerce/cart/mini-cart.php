<?php
defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_mini_cart' );
?>
<div class="novacore-mini-cart">
<?php if ( ! WC()->cart->is_empty() ) : ?>
	<ul class="woocommerce-mini-cart cart_list product_list_widget">
		<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php echo $thumbnail; ?>
					<div>
						<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
					</div>
				</li>
				<?php
			}
		}
		?>
	</ul>
	<div class="novacore-mini-cart__total">
		<span><?php esc_html_e( 'Subtotal', 'novacore' ); ?></span>
		<span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
	</div>
	<div class="novacore-mini-cart__buttons">
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="novacore-btn novacore-btn--ghost"><?php esc_html_e( 'View Cart', 'novacore' ); ?></a>
		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="novacore-btn novacore-btn--primary"><?php esc_html_e( 'Checkout', 'novacore' ); ?></a>
	</div>
<?php else : ?>
	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'novacore' ); ?></p>
<?php endif; ?>
</div>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
