<?php
defined( 'ABSPATH' ) || exit;
$total    = wc_get_loop_prop( 'total' );
$per_page = wc_get_loop_prop( 'per_page' );
$current  = wc_get_loop_prop( 'current_page' );
if ( $total <= $per_page || -1 === $per_page ) return;
?>
<p class="woocommerce-result-count">
	<?php
	$first = ( $current - 1 ) * $per_page + 1;
	$last  = min( $current * $per_page, $total );
	printf( esc_html__( 'Showing %1$d&ndash;%2$d of %3$d results', 'novacore' ), $first, $last, $total );
	?>
</p>
