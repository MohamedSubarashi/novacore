<?php
/**
 * NovaCore Theme Functions
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOVACORE_VERSION', '2.4.2' );
define( 'NOVACORE_DIR', get_template_directory() );
define( 'NOVACORE_URI', get_template_directory_uri() );
define( 'NOVACORE_ASSETS_DIR', NOVACORE_DIR . '/assets' );
define( 'NOVACORE_ASSETS_URI', NOVACORE_URI . '/assets' );
define( 'NOVACORE_BUILD_DIR', NOVACORE_DIR . '/build' );
define( 'NOVACORE_BUILD_URI', NOVACORE_URI . '/build' );
define( 'NOVACORE_INC_DIR', NOVACORE_DIR . '/inc' );
define( 'NOVACORE_BLOCKS_DIR', NOVACORE_DIR . '/blocks' );

require_once NOVACORE_INC_DIR . '/autoload.php';
require_once NOVACORE_INC_DIR . '/helpers/template-tags.php';

load_theme_textdomain( 'novacore', NOVACORE_DIR . '/languages' );

if ( ! isset( $GLOBALS['novacore'] ) ) {
	$GLOBALS['novacore'] = \NovaCore\Theme::instance();
}

do_action( 'novacore_loaded' );
