<?php
defined( 'ABSPATH' ) || exit;
wc_print_notices();
?>
<div class="novacore-error-404" style="min-height:40vh;">
	<div class="novacore-error-404__content">
		<h1 class="novacore-error-404__title"><?php esc_html_e( 'Your cart is empty', 'novacore' ); ?></h1>
		<p class="novacore-error-404__message"><?php esc_html_e( 'No items in your cart yet. Start shopping!', 'novacore' ); ?></p>
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="novacore-btn novacore-btn--primary">
			<?php esc_html_e( 'Return to Shop', 'novacore' ); ?>
		</a>
	</div>
</div>
