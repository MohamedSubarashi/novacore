<?php
defined( 'ABSPATH' ) || exit;
global $post, $product;
if ( $product->is_on_sale() ) :
	echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'novacore' ) . '</span>', $post, $product );
endif;
?>
