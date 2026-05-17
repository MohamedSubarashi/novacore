<?php
defined( 'ABSPATH' ) || exit;
$product_tabs = apply_filters( 'woocommerce_product_tabs', [] );
if ( empty( $product_tabs ) ) return;
?>
<div class="woocommerce-tabs wc-tabs-wrapper">
	<ul class="tabs wc-tabs" role="tablist">
		<?php foreach ( $product_tabs as $key => $tab ) : ?>
			<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
				<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php foreach ( $product_tabs as $key => $tab ) : ?>
		<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
			<?php call_user_func( $tab['callback'], $key, $tab ); ?>
		</div>
	<?php endforeach; ?>
	<?php do_action( 'woocommerce_product_after_tabs' ); ?>
</div>
