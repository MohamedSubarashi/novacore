<?php
/**
 * NovaCore Assets Manager
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'wp_head', [ $this, 'inline_customizer_css' ], 0 );
		add_filter( 'style_loader_tag', [ $this, 'style_attributes' ], 10, 4 );
		add_filter( 'script_loader_tag', [ $this, 'script_attributes' ], 10, 3 );

		// Prevent WordPress from auto-enqueuing root rtl.css (we enqueue build/ version manually)
		add_filter( 'locale_stylesheet_uri', '__return_empty_string', 99 );
	}

	public function enqueue_styles(): void {
		$css_file = '/build/css/main.min.css';
		$css_path = NOVACORE_DIR . $css_file;
		$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : NOVACORE_VERSION;

		wp_enqueue_style(
			'novacore-main',
			NOVACORE_URI . $css_file,
			[],
			$css_ver
		);

		if ( is_rtl() ) {
			$rtl_file = '/build/css/rtl.min.css';
			$rtl_path = NOVACORE_DIR . $rtl_file;
			$rtl_ver  = file_exists( $rtl_path ) ? filemtime( $rtl_path ) : NOVACORE_VERSION;
			wp_enqueue_style(
				'novacore-rtl',
				NOVACORE_URI . $rtl_file,
				[ 'novacore-main' ],
				$rtl_ver
			);
		}

		$dark_mode = $this->get_dark_mode();
		if ( 'enabled' === $dark_mode || 'system' === $dark_mode ) {
			$dark_file = '/build/css/dark.min.css';
			$dark_path = NOVACORE_DIR . $dark_file;
			$dark_ver  = file_exists( $dark_path ) ? filemtime( $dark_path ) : NOVACORE_VERSION;
			wp_enqueue_style(
				'novacore-dark',
				NOVACORE_URI . $dark_file,
				[ 'novacore-main' ],
				$dark_ver,
				'all'
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$wc_file = '/build/css/woocommerce.min.css';
			$wc_path = NOVACORE_DIR . $wc_file;
			$wc_ver  = file_exists( $wc_path ) ? filemtime( $wc_path ) : NOVACORE_VERSION;
			wp_enqueue_style(
				'novacore-woocommerce',
				NOVACORE_URI . $wc_file,
				[ 'novacore-main' ],
				$wc_ver
			);
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_style(
			'novacore-fontawesome',
			NOVACORE_ASSETS_URI . '/fonts/fontawesome/css/all.min.css',
			[],
			'6.7.2'
		);

		do_action( 'novacore_enqueue_styles' );
	}

	public function enqueue_scripts(): void {
		$js_file = '/build/js/main.min.js';
		$js_path = NOVACORE_DIR . $js_file;
		$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : NOVACORE_VERSION;

		wp_enqueue_script(
			'novacore-main',
			NOVACORE_URI . $js_file,
			[],
			$js_ver,
			true
		);

		wp_localize_script(
			'novacore-main',
			'novacoreData',
			$this->get_localized_data()
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$wc_js_file = '/build/js/woocommerce.min.js';
			$wc_js_path = NOVACORE_DIR . $wc_js_file;
			$wc_js_ver  = file_exists( $wc_js_path ) ? filemtime( $wc_js_path ) : NOVACORE_VERSION;
			wp_enqueue_script(
				'novacore-woocommerce',
				NOVACORE_URI . $wc_js_file,
				[ 'novacore-main' ],
				$wc_js_ver,
				true
			);
		}

		do_action( 'novacore_enqueue_scripts' );
	}

	public function editor_styles(): void {
		$editor_css = '/build/css/editor.min.css';
		$editor_path = NOVACORE_DIR . $editor_css;
		$editor_ver  = file_exists( $editor_path ) ? filemtime( $editor_path ) : NOVACORE_VERSION;

		add_editor_style( NOVACORE_URI . $editor_css . '?ver=' . $editor_ver );
	}

	public function admin_styles( string $hook ): void {
		$admin_css = '/build/css/admin.min.css';
		$admin_path = NOVACORE_DIR . $admin_css;
		$admin_ver  = file_exists( $admin_path ) ? filemtime( $admin_path ) : NOVACORE_VERSION;

		wp_enqueue_style(
			'novacore-admin',
			NOVACORE_URI . $admin_css,
			[],
			$admin_ver
		);
	}

	public function admin_scripts( string $hook ): void {
		$admin_js = '/build/js/admin.min.js';
		$admin_path = NOVACORE_DIR . $admin_js;
		$admin_ver  = file_exists( $admin_path ) ? filemtime( $admin_path ) : NOVACORE_VERSION;

		wp_enqueue_script(
			'novacore-admin',
			NOVACORE_URI . $admin_js,
			[],
			$admin_ver,
			true
		);
	}

	public function inline_customizer_css(): void {
		$css = '';

		$primary   = get_theme_mod( 'novacore_primary_color', '#16a34a' );
		$secondary = get_theme_mod( 'novacore_secondary_color', '#3b82f6' );
		$accent    = get_theme_mod( 'novacore_accent_color', '#3b82f6' );
		$container = get_theme_mod( 'novacore_container_width', 1280 );
		$layout    = get_theme_mod( 'novacore_site_layout', 'wide' );

		if ( '#16a34a' !== $primary || '#3b82f6' !== $secondary || '#3b82f6' !== $accent || 1280 !== $container || 'wide' !== $layout ) {
			$css .= '<style id="novacore-customizer-css">';
			$css .= ':root{';

			if ( '#16a34a' !== $primary ) {
				$css .= '--nc-primary:' . esc_attr( $primary ) . ';';
				$css .= '--nc-primary-hover:' . esc_attr( $this->darken_hex( $primary, 10 ) ) . ';';
			}
			if ( '#3b82f6' !== $secondary ) {
				$css .= '--nc-secondary:' . esc_attr( $secondary ) . ';';
			}
			if ( '#3b82f6' !== $accent ) {
				$css .= '--nc-accent:' . esc_attr( $accent ) . ';';
			}
			if ( 1280 !== $container ) {
				$css .= '--nc-container-width:' . absint( $container ) . 'px;';
			}
			$css .= '}';

			if ( 'boxed' === $layout ) {
				$css .= 'body.site-layout-boxed{max-width:1400px;margin:0 auto;box-shadow:0 0 30px rgba(0,0,0,.1)}';
			} elseif ( 'framed' === $layout ) {
				$css .= 'body.site-layout-framed{margin:20px;border:1px solid var(--nc-border)}';
			}

			$css .= '</style>';
		}

		if ( $css ) {
			echo $css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	private function darken_hex( string $color, int $percent ): string {
		$color = ltrim( $color, '#' );
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
		$r = max( 0, min( 255, $r - $r * $percent / 100 ) );
		$g = max( 0, min( 255, $g - $g * $percent / 100 ) );
		$b = max( 0, min( 255, $b - $b * $percent / 100 ) );
		return sprintf( '#%02x%02x%02x', (int) $r, (int) $g, (int) $b );
	}

	public function style_attributes( string $html, string $handle, string $href, string $media ): string {
		if ( strpos( $handle, 'novacore-' ) === 0 ) {
			$preload = "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"";
			if ( $media && 'all' !== $media ) {
				$preload .= " media='{$media}'";
			}
			$html = str_replace(
				"rel='stylesheet'",
				$preload,
				$html
			);
		}
		return $html;
	}

	public function script_attributes( string $tag, string $handle, string $src ): string {
		if ( strpos( $handle, 'novacore-' ) === 0 ) {
			$wp_scripts = wp_scripts();
			$has_inline = ! empty( $wp_scripts->registered[ $handle ]->extra['before'] )
				|| ! empty( $wp_scripts->registered[ $handle ]->extra['after'] )
				|| ! empty( $wp_scripts->registered[ $handle ]->extra['data'] );
			if ( ! $has_inline ) {
				$tag = str_replace( 'src=', 'defer src=', $tag );
			}
		}
		return $tag;
	}

	private function get_localized_data(): array {
		$data = [
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'restUrl'       => rest_url( 'novacore/v1/' ),
			'nonce'         => wp_create_nonce( 'novacore_nonce' ),
			'darkMode'      => $this->get_dark_mode(),
			'isRTL'         => is_rtl(),
			'infiniteScroll' => get_theme_mod( 'novacore_blog_infinite_scroll', false ),
			'ajaxPagination' => get_theme_mod( 'novacore_ajax_pagination', false ),
			'stickyHeader'  => get_theme_mod( 'novacore_sticky_header', true ),
			'animations'    => get_theme_mod( 'novacore_animations', true ),
		];

		if ( ( is_home() || is_archive() || is_search() ) && get_theme_mod( 'novacore_ajax_pagination', false ) ) {
			global $wp_query;
			$safe_vars = [];
			$allowed   = [ 'post_type', 'posts_per_page', 'orderby', 'order', 'cat', 'tag_id', 'author', 's', 'tax_query', 'meta_query', 'date_query', 'year', 'monthnum', 'day', 'post_status' ];
			foreach ( $allowed as $key ) {
				if ( isset( $wp_query->query_vars[ $key ] ) ) {
					$safe_vars[ $key ] = $wp_query->query_vars[ $key ];
				}
			}
			$data['currentQuery'] = wp_json_encode( $safe_vars );
		} else {
			$data['currentQuery'] = '';
		}

		return $data;
	}

	private function get_dark_mode(): string {
		return get_theme_mod( 'novacore_dark_mode', 'system' );
	}
}
