<?php
/**
 * Site Branding Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$nc_header_img = get_header_image();
?>
<div class="novacore-header__brand">
	<?php if ( $nc_header_img ) : ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="novacore-header__brand-img-link" rel="home">
			<img src="<?php echo esc_url( $nc_header_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="novacore-header__brand-img" width="175" height="100">
		</a>
	<?php elseif ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
	<?php else : ?>
		<?php printf(
			'<a href="%s" class="novacore-logo" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) )
		); ?>
	<?php endif; ?>
</div>
