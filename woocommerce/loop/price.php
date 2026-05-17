<?php
defined( 'ABSPATH' ) || exit;
global $product;
?>
<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price"><?php echo wp_kses_post( $price_html ); ?></span>
<?php endif; ?>
