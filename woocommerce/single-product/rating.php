<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! wc_review_ratings_enabled() ) return;
$rating = $product->get_average_rating();
?>
<div class="woocommerce-product-rating">
	<?php echo wc_get_rating_html( $rating ); ?>
	<?php if ( comments_open() ) : ?>
		<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
			<?php printf( _n( '%s review', '%s reviews', $product->get_review_count(), 'novacore' ), '<span class="count">' . esc_html( $product->get_review_count() ) . '</span>' ); ?>
		</a>
	<?php endif; ?>
</div>
