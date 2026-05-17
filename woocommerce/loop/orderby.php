<?php
defined( 'ABSPATH' ) || exit;
if ( ! wc_get_loop_prop( 'is_filtered' ) || ! wc_get_loop_prop( 'total' ) ) return;
?>
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'novacore' ); ?>">
		<?php foreach ( wc_get_catalog_ordering_args() as $key => $value ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value['orderby'], '' ); ?>>
				<?php echo esc_html( $value['name'] ?? $key ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, [ 'orderby', 'submit', 'paged', 'product-page' ] ); ?>
</form>
