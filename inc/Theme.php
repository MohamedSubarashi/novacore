<?php
/**
 * NovaCore Main Theme Class
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore;

use NovaCore\Core\Assets;
use NovaCore\Core\Setup;
use NovaCore\Core\Customizer;
use NovaCore\Core\Header_Builder;
use NovaCore\Core\Footer_Builder;
use NovaCore\Core\Performance;
use NovaCore\Core\SEO;
use NovaCore\Core\Dashboard;
use NovaCore\Core\Demo_Import;
use NovaCore\Core\AI;
use NovaCore\Core\Blocks;
use NovaCore\Core\Ads;
use NovaCore\Ajax;

defined( 'ABSPATH' ) || exit;

/**
 * Main Theme Class - Singleton
 */
final class Theme {

	private static ?Theme $instance = null;

	public Assets $assets;
	public Setup $setup;
	public Customizer $customizer;
	public Header_Builder $header_builder;
	public Footer_Builder $footer_builder;

	public static function instance(): Theme {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_hooks();
	}

	private function init_hooks(): void {
		add_action( 'after_setup_theme', [ $this, 'setup' ], 10 );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
	}

	public function register_widgets(): void {
		$widgets = [
			'NovaCore\\Widgets\\Recent_Posts',
			'NovaCore\\Widgets\\Social_Counter',
			'NovaCore\\Widgets\\Newsletter',
			'NovaCore\\Widgets\\Author_Card',
			'NovaCore\\Widgets\\Ad',
			'NovaCore\\Widgets\\Post_Slider',
		];

		foreach ( $widgets as $widget ) {
			if ( class_exists( $widget ) ) {
				register_widget( $widget );
			}
		}

		do_action( 'novacore_register_widgets' );
	}

	public function setup(): void {
		$this->setup   = new Setup();
		$this->assets  = new Assets();
		$this->customizer = new Customizer();

		add_action( 'init', [ $this, 'init_components' ], 20 );

		do_action( 'novacore_after_setup' );
	}

	public function init_components(): void {
		$this->header_builder = Header_Builder::instance();
		$this->footer_builder = Footer_Builder::instance();

		new Performance();
		new SEO();
		new Blocks();
		new Ads();

		if ( is_admin() ) {
			new Dashboard();
			new Demo_Import();
			new AI();
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Ajax();
		}

		do_action( 'novacore_init_components' );
	}

	public function content_width(): void {
		$GLOBALS['content_width'] = apply_filters( 'novacore_content_width', 1280 );
	}

	public function register_sidebars(): void {
		$defaults = [
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		];

		register_sidebar(
			wp_parse_args(
				[
					'id'   => 'sidebar-1',
					'name' => esc_html__( 'Default Sidebar', 'novacore' ),
				],
				$defaults
			)
		);

		register_sidebar(
			wp_parse_args(
				[
					'id'   => 'sidebar-shop',
					'name' => esc_html__( 'Shop Sidebar', 'novacore' ),
				],
				$defaults
			)
		);

		$footer_columns = apply_filters( 'novacore_footer_widget_columns', 4 );
		for ( $i = 1; $i <= $footer_columns; $i++ ) {
			register_sidebar(
				wp_parse_args(
					[
						'id'   => "footer-{$i}",
						'name' => sprintf(
							esc_html__( 'Footer Widget %d', 'novacore' ),
							$i
						),
					],
					$defaults
				)
			);
		}

		register_sidebar(
			wp_parse_args(
				[
					'id'   => 'off-canvas',
					'name'        => esc_html__( 'Off Canvas', 'novacore' ),
					'description' => esc_html__( 'Widgets shown in the off-canvas panel.', 'novacore' ),
				],
				$defaults
			)
		);

		register_sidebar(
			wp_parse_args(
				[
					'id'          => 'sidebar-sticky-top',
					'name'        => esc_html__( 'Sidebar: Sticky Top', 'novacore' ),
					'description' => esc_html__( 'Sticky widget area at the top of the sidebar (useful for ads).', 'novacore' ),
				],
				$defaults
			)
		);

		register_sidebar(
			wp_parse_args(
				[
					'id'          => 'sidebar-trending',
					'name'        => esc_html__( 'Sidebar: Trending Now', 'novacore' ),
					'description' => esc_html__( 'Trending posts with numbered list styling.', 'novacore' ),
				],
				$defaults
			)
		);

		register_sidebar(
			wp_parse_args(
				[
					'id'          => 'sidebar-cta',
					'name'        => esc_html__( 'Sidebar: Call to Action', 'novacore' ),
					'description' => esc_html__( 'Newsletter signup, follow, or CTA area.', 'novacore' ),
				],
				$defaults
			)
		);

		register_sidebar(
			wp_parse_args(
				[
					'id'          => 'sidebar-bottom',
					'name'        => esc_html__( 'Sidebar: Bottom', 'novacore' ),
					'description' => esc_html__( 'Bottom widget area in the sidebar.', 'novacore' ),
				],
				$defaults
			)
		);

		$ad_areas = [
			'ad-header'         => esc_html__( 'Ad: Header', 'novacore' ),
			'ad-before-content' => esc_html__( 'Ad: Before Content', 'novacore' ),
			'ad-after-content'  => esc_html__( 'Ad: After Content', 'novacore' ),
			'ad-before-footer'  => esc_html__( 'Ad: Before Footer', 'novacore' ),
			'ad-1'              => esc_html__( 'Ad 1: Header Brand', 'novacore' ),
			'ad-2'              => esc_html__( 'Ad 2: Below Slideshow', 'novacore' ),
			'ad-3'              => esc_html__( 'Ad 3: Post Top', 'novacore' ),
			'ad-4'              => esc_html__( 'Ad 4: Post Middle', 'novacore' ),
			'ad-5'              => esc_html__( 'Ad 5: Post Bottom', 'novacore' ),
			'ad-6'              => esc_html__( 'Ad 6: Footer Top', 'novacore' ),
		];

		foreach ( $ad_areas as $id => $name ) {
			register_sidebar(
				wp_parse_args(
					[
						'id'          => $id,
						'name'        => $name,
						'description' => esc_html__( 'Advertisement widget area.', 'novacore' ),
					],
					$defaults
				)
			);
		}

		do_action( 'novacore_register_sidebars' );
	}

	private function __clone() {}
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}
}
