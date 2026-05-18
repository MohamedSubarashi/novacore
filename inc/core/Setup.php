<?php
/**
 * NovaCore Theme Setup
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Setup {

	public function __construct() {
		load_theme_textdomain( 'novacore', NOVACORE_DIR . '/languages' );
		$this->theme_supports();
		$this->register_menus();
		$this->image_sizes();
		$this->hooks();
	}

	private function theme_supports(): void {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', [
			'flex-height' => true,
			'flex-width'  => true,
		] );
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		] );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'post-formats', [ 'video' ] );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'block-template-parts' );
		add_theme_support( 'block-nav-menus' );
		add_theme_support( 'link-color' );
		add_theme_support( 'border' );
		add_theme_support( 'custom-spacing' );
		add_theme_support( 'custom-units', [ 'px', 'em', 'rem', 'vh', 'vw', '%' ] );
		add_theme_support( 'custom-header', [
			'default-image'      => '',
			'default-text-color' => '1e293b',
			'width'              => 1920,
			'height'             => 400,
			'flex-height'        => true,
			'flex-width'         => true,
			'wp-head-callback'   => '__return_false',
		] );
		add_theme_support( 'custom-background', [
			'default-color' => 'ffffff',
		] );

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		if ( class_exists( 'BuddyPress' ) ) {
			add_theme_support( 'buddypress' );
		}

		if ( class_exists( 'bbPress' ) ) {
			add_theme_support( 'bbpress' );
		}

		add_theme_support( 'infinite-scroll', [
			'type'      => 'scroll',
			'container' => 'main-content',
			'render'    => [ $this, 'infinite_scroll_render' ],
			'footer'    => false,
		] );

		add_theme_support( 'jetpack-responsive-videos' );

		do_action( 'novacore_theme_supports' );
	}

	private function register_menus(): void {
		register_nav_menus( [
			'primary'   => esc_html__( 'Primary Menu', 'novacore' ),
			'secondary' => esc_html__( 'Secondary Menu', 'novacore' ),
			'mobile'    => esc_html__( 'Mobile Menu', 'novacore' ),
			'footer'    => esc_html__( 'Footer Menu', 'novacore' ),
			'top-bar'   => esc_html__( 'Top Bar Menu', 'novacore' ),
		] );
	}

	private function image_sizes(): void {
		add_image_size( 'novacore-sm', 400, 300, true );
		add_image_size( 'novacore-md', 768, 512, true );
		add_image_size( 'novacore-lg', 1200, 675, true );
		add_image_size( 'novacore-xl', 1920, 1080, true );
	}

	private function hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_fonts' ], 5 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_fonts' ] );
		add_action( 'pre_get_posts', [ $this, 'posts_per_page' ] );
		add_action( 'after_switch_theme', [ $this, 'create_default_menus' ] );
		add_action( 'wp_head', [ $this, 'header_image_style' ], 10 );
	}

	public function posts_per_page( \WP_Query $query ): void {
		if ( ! is_admin() && $query->is_main_query() && ! $query->is_feed() && ( is_home() || is_archive() ) ) {
			$query->set( 'posts_per_page', 6 );
		}
	}

	public function enqueue_fonts(): void {
		if ( get_theme_mod( 'novacore_local_fonts', false ) ) {
			$this->enqueue_local_fonts();
		} else {
			$fonts_url = $this->get_google_fonts_url();
			if ( $fonts_url ) {
				wp_enqueue_style( 'novacore-google-fonts', $fonts_url, [], NOVACORE_VERSION );
			}
		}
	}

	public function editor_fonts(): void {
		if ( get_theme_mod( 'novacore_local_fonts', false ) ) {
			$this->enqueue_local_fonts();
		} else {
			$fonts_url = $this->get_google_fonts_url();
			if ( $fonts_url ) {
				add_editor_style( str_replace( ',', '%2C', $fonts_url ) );
			}
		}
	}

	public function enqueue_local_fonts(): void {
		$fonts_dir = NOVACORE_ASSETS_DIR . '/fonts';
		$fonts_uri = NOVACORE_ASSETS_URI . '/fonts';

		if ( ! is_dir( $fonts_dir ) ) {
			wp_mkdir_p( $fonts_dir );
		}

		$cache_key = 'novacore_fonts_css';
		$css = get_transient( $cache_key );

		if ( false === $css ) {
			$remote_url = $this->get_google_fonts_url();
			if ( ! $remote_url ) {
				return;
			}

			$response = wp_remote_get( $remote_url, [ 'timeout' => 15 ] );
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return;
			}

			$css = wp_remote_retrieve_body( $response );
			$css = $this->localize_font_urls( $css, $fonts_uri, $fonts_dir );
			set_transient( $cache_key, $css, WEEK_IN_SECONDS );
		}

		if ( ! empty( $css ) ) {
			wp_register_style( 'novacore-google-fonts', false, [], NOVACORE_VERSION );
			wp_enqueue_style( 'novacore-google-fonts' );
			wp_add_inline_style( 'novacore-google-fonts', $css );
		}
	}

	private function localize_font_urls( string $css, string $fonts_uri, string $fonts_dir ): string {
		return preg_replace_callback(
			'#url\((https?://[^)]+)\)#',
			function ( $matches ) use ( $fonts_uri, $fonts_dir ) {
				$remote_url = $matches[1];
				$ext = pathinfo( parse_url( $remote_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) ?: 'woff2';
				$filename = 'font-' . md5( $remote_url ) . '.' . $ext;
				$local_path = $fonts_dir . '/' . $filename;

				if ( ! file_exists( $local_path ) ) {
					$response = wp_remote_get( $remote_url, [ 'timeout' => 10, 'stream' => true, 'filename' => $local_path ] );
					if ( is_wp_error( $response ) ) {
						return $matches[0];
					}
				}

				return 'url(' . $fonts_uri . '/' . $filename . ')';
			},
			$css
		);
	}

	public function get_google_fonts_url(): string {
		$fonts = apply_filters( 'novacore_google_fonts', [
			'Inter:wght@300;400;500;600;700;800',
			'DM+Sans:wght@400;500;700',
		] );

		if ( empty( $fonts ) ) {
			return '';
		}

		$font_display = apply_filters( 'novacore_google_fonts_display', 'swap' );
		$args = [
			'family'  => implode( '&family=', $fonts ),
			'display' => $font_display,
		];

		return add_query_arg( $args, 'https://fonts.googleapis.com/css2' );
	}

	public function infinite_scroll_render(): void {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content/content', get_post_type() );
		}
	}

	public function create_default_menus(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$locations = get_nav_menu_locations();

		if ( ! empty( $locations['primary'] ) ) {
			return;
		}

		$menus = [
			'primary' => [
				'title'  => esc_html__( 'Primary Menu', 'novacore' ),
				'items'  => [
					[
						'title' => esc_html__( 'Home', 'novacore' ),
						'url'   => home_url( '/' ),
					],
					[
						'title' => esc_html__( 'Blog', 'novacore' ),
						'url'   => home_url( '/blog/' ),
					],
					[
						'title' => esc_html__( 'About', 'novacore' ),
						'url'   => home_url( '/about/' ),
					],
					[
						'title' => esc_html__( 'Contact', 'novacore' ),
						'url'   => home_url( '/contact/' ),
					],
				],
			],
			'top-bar' => [
				'title'  => esc_html__( 'Top Bar', 'novacore' ),
				'items'  => [
					[
						'title' => esc_html__( 'Login', 'novacore' ),
						'url'   => wp_login_url(),
					],
				],
			],
			'footer'  => [
				'title'  => esc_html__( 'Footer Menu', 'novacore' ),
				'items'  => [
					[
						'title' => esc_html__( 'Privacy Policy', 'novacore' ),
						'url'   => home_url( '/privacy-policy/' ),
					],
					[
						'title' => esc_html__( 'Terms of Service', 'novacore' ),
						'url'   => home_url( '/terms/' ),
					],
					[
						'title' => esc_html__( 'Sitemap', 'novacore' ),
						'url'   => home_url( '/sitemap/' ),
					],
				],
			],
		];

		foreach ( $menus as $location => $menu_data ) {
			$menu_id = wp_create_nav_menu( $menu_data['title'] );

			if ( is_wp_error( $menu_id ) ) {
				continue;
			}

			foreach ( $menu_data['items'] as $item ) {
				wp_update_nav_menu_item( $menu_id, 0, [
					'menu-item-title'  => $item['title'],
					'menu-item-url'    => $item['url'],
					'menu-item-status' => 'publish',
				] );
			}

			$locations[ $location ] = $menu_id;
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	public function header_image_style(): void {
		$css = '';

		$text_color = get_header_textcolor();
		if ( 'blank' === $text_color ) {
			$css .= '.novacore-logo{position:absolute;overflow:hidden;clip:rect(1px,1px,1px,1px);width:1px;height:1px}';
		} elseif ( preg_match( '/^[a-f0-9]{6}$/i', $text_color ) ) {
			$css .= '.novacore-logo{color:#' . esc_attr( $text_color ) . '}';
		}

		if ( $css ) {
			echo '<style>' . $css . '</style>' . "\n";
		}
	}
}
