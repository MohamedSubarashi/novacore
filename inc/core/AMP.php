<?php
/**
 * NovaCore AMP Compatibility
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class AMP {

	public function __construct() {
		if ( ! defined( 'AMP__VERSION' ) ) {
			return;
		}

		add_action( 'amp_post_template_css', [ $this, 'add_amp_styles' ] );
		add_filter( 'amp_content_max_width', [ $this, 'content_max_width' ] );
		add_filter( 'amp_site_icon_url', [ $this, 'site_icon' ] );
	}

	public function add_amp_styles(): void {
		$css_file = NOVACORE_BUILD_DIR . '/css/main.min.css';
		if ( file_exists( $css_file ) ) {
			echo file_get_contents( $css_file );
		}
	}

	public function content_max_width(): int {
		return 1280;
	}

	public function site_icon(): string {
		return get_site_icon_url() ?: '';
	}
}
