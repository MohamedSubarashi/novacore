<?php
defined( 'ABSPATH' ) || exit;
if ( $order ) :
	do_action( 'woocommerce_before_thankyou', $order->get_id() );
?>
<div class="woocommerce-order">
	<?php if ( $order->has_status( 'failed' ) ) : ?>
		<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'novacore' ); ?></p>
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="novacore-btn novacore-btn--primary"><?php esc_html_e( 'Pay', 'novacore' ); ?></a>
	<?php else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'novacore' ), $order ); ?></p>
		<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
			<li class="woocommerce-order-overview__order order"><?php esc_html_e( 'Order number:', 'novacore' ); ?> <strong><?php echo $order->get_order_number(); ?></strong></li>
			<li class="woocommerce-order-overview__date date"><?php esc_html_e( 'Date:', 'novacore' ); ?> <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong></li>
			<li class="woocommerce-order-overview__total total"><?php esc_html_e( 'Total:', 'novacore' ); ?> <strong><?php echo $order->get_formatted_order_total(); ?></strong></li>
			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="woocommerce-order-overview__payment payment"><?php esc_html_e( 'Payment method:', 'novacore' ); ?> <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong></li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
	<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
</div>
<?php endif; ?>
