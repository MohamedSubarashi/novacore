<?php
defined( 'ABSPATH' ) || exit;
$current_user = wp_get_current_user();
?>
<p><?php printf( esc_html__( 'Hello %s (not %2$s? %3$s)', 'novacore' ), '<strong>' . esc_html( $current_user->display_name ) . '</strong>', '<a href="' . esc_url( wc_logout_url() ) . '">' . esc_html__( 'Log out', 'novacore' ) . '</a>' ); ?></p>
<p><?php printf( esc_html__( 'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sshipping and billing addresses%2$s, and %4$sedit your password and account details%2$s.', 'novacore' ), '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">', '</a>', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address' ) ) . '">', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-account' ) ) . '">' ); ?></p>
<?php do_action( 'woocommerce_account_dashboard' ); ?>
