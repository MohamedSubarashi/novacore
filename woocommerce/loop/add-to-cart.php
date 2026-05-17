<?php
defined( 'ABSPATH' ) || exit;
global $product;
echo apply_filters(
	'woocommerce_loop_add_to_cart_link',
	sprintf(
		'<a href="%s" data-product_id="%s" data-product_sku="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $product->get_id() ),
		esc_attr( $product->get_sku() ),
		esc_attr( implode( ' ', array_filter( [
			'novacore-btn',
			'novacore-btn--primary',
			'novacore-btn--sm',
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
		] ) ) ),
		$product->is_type( 'variable' ) ? '' : '',
		esc_html( $product->add_to_cart_text() )
	),
	$product
);
?>
