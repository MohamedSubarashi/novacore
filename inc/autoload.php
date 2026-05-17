<?php
/**
 * NovaCore PSR-4 Autoloader
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	function ( string $class ): void {
		$prefix   = 'NovaCore\\';
		$base_dir = NOVACORE_INC_DIR . '/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );

		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( strpos( $relative_class, 'Widgets\\' ) === 0 ) {
			$file = $base_dir . 'widgets/' . substr( $relative_class, 8 ) . '.php';
		} elseif ( 'Ajax' === $relative_class ) {
			$file = $base_dir . 'ajax.php';
		}

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);
