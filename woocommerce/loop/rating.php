<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! wc_review_ratings_enabled() ) return;
$rating = $product->get_average_rating();
if ( $rating > 0 ) :
	echo wc_get_rating_html( $rating );
endif;
?>
