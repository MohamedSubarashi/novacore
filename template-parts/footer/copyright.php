<?php
/**
 * Footer Copyright Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'dynamic_footer_copyright' ) ) {
    function dynamic_footer_copyright( $prefix = '' ) {
        $year       = gmdate( 'Y' );
        $site_name  = get_bloginfo( 'name' );
        $site_link  = '<a href="' . esc_url( home_url( '/' ) ) . '" class="novacore-footer__site-name">' . esc_html( $site_name ) . '</a>';
        $theme_name = wp_get_theme()->get( 'Name' );
        $tagline    = get_theme_mod( 'novacore_copyright_tagline', esc_html__( 'All rights reserved', 'novacore' ) );
        return $prefix . "Copyright &copy; $year | $site_link | Powered by $theme_name" . " - " . $tagline;
    }
}
?>
<div class="novacore-footer__copyright">
	<?php echo wp_kses_post( dynamic_footer_copyright() ); ?>
</div>