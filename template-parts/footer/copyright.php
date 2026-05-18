<?php
/**
 * Footer Copyright Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="novacore-footer__copyright">
	Copyright &copy; <?php echo gmdate( 'Y' ); ?>
	| <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
	| NovaCore WordPress Theme <?php echo esc_html( wp_get_theme()->get( 'Author' ) ); ?>
	is distributed under the
	<a href="<?php echo esc_url( wp_get_theme()->get( 'License URI' ) ); ?>" rel="license">GNU GPL License</a>.
</div>
