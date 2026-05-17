<?php
defined( 'ABSPATH' ) || exit;
if ( $breadcrumb ) :
	echo '<nav class="novacore-breadcrumbs woocommerce-breadcrumb">';
	foreach ( $breadcrumb as $key => $crumb ) :
		echo $crumb[1] ? '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>' : '<span class="current">' . esc_html( $crumb[0] ) . '</span>';
		if ( $key < count( $breadcrumb ) - 1 ) echo ' <span class="separator">/</span> ';
	endforeach;
	echo '</nav>';
endif;
?>
