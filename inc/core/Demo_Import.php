<?php
/**
 * NovaCore Demo Import & Export
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Demo_Import {

	private array $demos = [];

	public function __construct() {
		$this->demos = apply_filters( 'novacore_demo_list', [
			'agency' => [
				'name'        => __( 'Agency', 'novacore' ),
				'description' => __( 'Modern agency website with portfolio focus.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/agency/content.xml',
			],
			'blog' => [
				'name'        => __( 'Blog', 'novacore' ),
				'description' => __( 'Clean blog layout with multiple post formats.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/blog/content.xml',
			],
			'saas' => [
				'name'        => __( 'SaaS', 'novacore' ),
				'description' => __( 'SaaS landing page with pricing and features.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/saas/content.xml',
			],
			'news' => [
				'name'        => __( 'News', 'novacore' ),
				'description' => __( 'News magazine layout with featured stories.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/news/content.xml',
			],
			'portfolio' => [
				'name'        => __( 'Portfolio', 'novacore' ),
				'description' => __( 'Creative portfolio for designers & artists.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/portfolio/content.xml',
			],
			'ecommerce' => [
				'name'        => __( 'eCommerce', 'novacore' ),
				'description' => __( 'WooCommerce shop with product-focused design.', 'novacore' ),
				'file'        => NOVACORE_DIR . '/demo-content/ecommerce/content.xml',
			],
		] );

		add_action( 'wp_ajax_novacore_import_demo', [ $this, 'handle_import' ] );
		add_action( 'wp_ajax_novacore_export_settings', [ $this, 'handle_export_settings' ] );
		add_action( 'wp_ajax_novacore_import_settings', [ $this, 'handle_import_settings' ] );
	}

	public function get_demos(): array {
		return $this->demos;
	}

	public function handle_import(): void {
		check_ajax_referer( 'novacore_nonce', '_ajax_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Insufficient permissions.', 'novacore' ) );
		}

		$demo = isset( $_POST['demo'] ) ? sanitize_text_field( wp_unslash( $_POST['demo'] ) ) : '';

		if ( ! isset( $this->demos[ $demo ] ) ) {
			wp_send_json_error( __( 'Invalid demo.', 'novacore' ) );
		}

		if ( ! file_exists( $this->demos[ $demo ]['file'] ) ) {
			wp_send_json_error( __( 'Demo content file not found.', 'novacore' ) );
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			wp_send_json_error( __( 'WordPress Importer plugin is required to import demo content.', 'novacore' ) );
			return;
		}

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		set_time_limit( 0 );

		ob_start();

		try {
			$importer = new \WP_Import();
			$importer->fetch_attachments = true;
			$importer->import( $this->demos[ $demo ]['file'] );
		} catch ( \Throwable $e ) {
			ob_end_clean();
			wp_send_json_error( $e->getMessage() );
		}

		ob_end_clean();

		$this->set_imported_demo( $demo );

		do_action( 'novacore_demo_imported', $demo );

		wp_send_json_success( __( 'Demo imported successfully.', 'novacore' ) );
	}

	/**
	 * Export all theme settings as JSON.
	 */
	public function handle_export_settings(): void {
		check_ajax_referer( 'novacore_nonce', '_ajax_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Insufficient permissions.', 'novacore' ) );
		}

		$package = $this->export_package();

		wp_send_json_success( $package );
	}

	/**
	 * Import theme settings from JSON.
	 */
	public function handle_import_settings(): void {
		check_ajax_referer( 'novacore_nonce', '_ajax_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Insufficient permissions.', 'novacore' ) );
		}

		if ( ! isset( $_FILES['file'] ) || UPLOAD_ERR_OK !== $_FILES['file']['error'] ) {
			wp_send_json_error( __( 'No file uploaded or upload error.', 'novacore' ) );
		}

		$json = file_get_contents( $_FILES['file']['tmp_name'] ); // phpcs:ignore
		$data = json_decode( $json, true );

		if ( ! $data || ! isset( $data['_export_version'] ) ) {
			wp_send_json_error( __( 'Invalid settings file.', 'novacore' ) );
		}

		$this->import_package( $data );

		wp_send_json_success( __( 'Settings imported successfully.', 'novacore' ) );
	}

	/**
	 * Build an export package array.
	 */
	public function export_package(): array {
		$theme = wp_get_theme();
		$mods  = get_theme_mods();

		$widgets_data = [];
		$widget_factory = wp_get_sidebars_widgets();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar_id => $sidebar ) {
			if ( isset( $widget_factory[ $sidebar_id ] ) ) {
				$widgets_data[ $sidebar_id ] = [
					'name'   => $sidebar['name'],
					'widgets' => [],
				];
				foreach ( $widget_factory[ $sidebar_id ] as $widget_id ) {
					$widget_type = _get_widget_id_base( $widget_id );
					$widget_number = (int) str_replace( $widget_type . '-', '', $widget_id );
					$all_instances = get_option( 'widget_' . $widget_type, [] );
					if ( isset( $all_instances[ $widget_number ] ) ) {
						$widgets_data[ $sidebar_id ]['widgets'][] = [
							'type'     => $widget_type,
							'number'   => $widget_number,
							'settings' => $all_instances[ $widget_number ],
						];
					}
				}
			}
		}

		$menus = wp_get_nav_menus();
		$menu_data = [];
		foreach ( $menus as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu->term_id, [ 'update_post_term_cache' => false ] );
			$items = [];
			if ( ! empty( $menu_items ) ) {
				foreach ( $menu_items as $item ) {
					$items[] = [
						'title'      => $item->title,
						'url'        => $item->url,
						'type'       => $item->type,
						'object'     => $item->object,
						'object_id'  => $item->object_id,
						'parent'     => $item->menu_item_parent,
						'classes'    => $item->classes,
						'target'     => $item->target,
						'attr_title' => $item->attr_title,
					];
				}
			}
			$menu_data[] = [
				'name'        => $menu->name,
				'slug'        => $menu->slug,
				'items'       => $items,
			];
		}

		$menu_locations = get_nav_menu_locations();

		$homepage = [
			'show_on_front' => get_option( 'show_on_front' ),
			'page_on_front' => get_option( 'page_on_front' ),
			'page_for_posts' => get_option( 'page_for_posts' ),
		];

		return [
			'_export_version'  => '1.0',
			'_export_date'     => current_time( 'mysql' ),
			'_theme'           => $theme->get_template(),
			'_theme_name'      => $theme->get( 'Name' ),
			'_wp_version'      => get_bloginfo( 'version' ),
			'theme_mods'       => $mods,
			'widgets'          => $widgets_data,
			'menus'            => $menu_data,
			'menu_locations'   => $menu_locations,
			'homepage'         => $homepage,
			'site_structure'   => $this->get_site_structure_doc(),
		];
	}

	/**
	 * Import settings from a package array.
	 */
	public function import_package( array $data ): void {
		// Import theme mods.
		if ( isset( $data['theme_mods'] ) && is_array( $data['theme_mods'] ) ) {
			$theme = get_option( 'theme_mods_' . get_template() );
			if ( ! is_array( $theme ) ) {
				$theme = [];
			}
			foreach ( $data['theme_mods'] as $key => $value ) {
				$theme[ $key ] = $value;
			}
			update_option( 'theme_mods_' . get_template(), $theme );
		}

		// Import widget settings.
		if ( isset( $data['widgets'] ) && is_array( $data['widgets'] ) ) {
			foreach ( $data['widgets'] as $sidebar_id => $sidebar_data ) {
				foreach ( $sidebar_data['widgets'] as $widget_info ) {
					$widget_type = $widget_info['type'];
					$all_instances = get_option( 'widget_' . $widget_type, [] );
					$next_number = 1;
					if ( ! empty( $all_instances ) ) {
						$keys = array_keys( $all_instances );
						$numeric = array_filter( $keys, 'is_int' );
						$next_number = $numeric ? max( $numeric ) + 1 : 1;
					}
					$all_instances[ $next_number ] = $widget_info['settings'];
					update_option( 'widget_' . $widget_type, $all_instances );

					// Assign to sidebar.
					$sidebars_widgets = wp_get_sidebars_widgets();
					$sidebars_widgets[ $sidebar_id ][] = $widget_type . '-' . $next_number;
					wp_set_sidebars_widgets( $sidebars_widgets );
				}
			}
		}

		// Import menus.
		if ( isset( $data['menus'] ) && is_array( $data['menus'] ) ) {
			foreach ( $data['menus'] as $menu_info ) {
				$existing = wp_get_nav_menu_object( $menu_info['slug'] );
				if ( $existing ) {
					continue;
				}
				$menu_id = wp_create_nav_menu( $menu_info['name'] );
				if ( is_wp_error( $menu_id ) ) {
					continue;
				}
				if ( isset( $menu_info['items'] ) ) {
					foreach ( $menu_info['items'] as $item ) {
						wp_update_nav_menu_item( $menu_id, 0, [
							'menu-item-title'  => $item['title'],
							'menu-item-url'    => $item['url'],
							'menu-item-type'   => $item['type'],
							'menu-item-object' => $item['object'],
							'menu-item-object-id' => $item['object_id'],
							'menu-item-parent-id' => 0,
							'menu-item-target' => $item['target'] ?? '',
							'menu-item-attr-title' => $item['attr_title'] ?? '',
							'menu-item-classes' => is_array( $item['classes'] ) ? implode( ' ', $item['classes'] ) : '',
						] );
					}
				}
			}
		}

		// Import menu locations.
		if ( isset( $data['menu_locations'] ) && is_array( $data['menu_locations'] ) ) {
			set_theme_mod( 'nav_menu_locations', $data['menu_locations'] );
		}

		// Import homepage settings.
		if ( isset( $data['homepage'] ) ) {
			foreach ( $data['homepage'] as $key => $value ) {
				update_option( $key, $value );
			}
		}

		do_action( 'novacore_settings_imported', $data );
	}

	/**
	 * Get the current site structure documentation.
	 */
	public function get_site_structure_doc(): array {
		$theme = wp_get_theme();

		$sidebars = [];
		foreach ( $GLOBALS['wp_registered_sidebars'] as $id => $sidebar ) {
			$sidebars[ $id ] = [
				'name'        => $sidebar['name'],
				'description' => $sidebar['description'] ?? '',
			];
		}

		$menus = [];
		$registered_menus = get_registered_nav_menus();
		foreach ( $registered_menus as $location => $description ) {
			$menus[ $location ] = $description;
		}

		$blocks = [];
		$block_dirs = glob( NOVACORE_DIR . '/blocks/*', GLOB_ONLYDIR );
		if ( $block_dirs ) {
			foreach ( $block_dirs as $dir ) {
				$json_file = $dir . '/block.json';
				if ( file_exists( $json_file ) ) {
					$block_data = json_decode( file_get_contents( $json_file ), true );
					if ( $block_data ) {
						$blocks[] = [
							'name'    => $block_data['name'] ?? basename( $dir ),
							'title'   => $block_data['title'] ?? '',
							'description' => $block_data['description'] ?? '',
							'icon'    => $block_data['icon'] ?? '',
						];
					}
				}
			}
		}

		return [
			'theme'           => $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ),
			'template'        => $theme->get_template(),
			'description'     => $theme->get( 'Description' ),
			'features' => [
				'block_editor'     => current_theme_supports( 'editor-styles' ),
				'block_templates'  => current_theme_supports( 'block-template-parts' ),
				'custom_logo'      => current_theme_supports( 'custom-logo' ),
				'custom_header'    => current_theme_supports( 'custom-header' ),
				'woocommerce'      => current_theme_supports( 'woocommerce' ),
				'align_wide'       => current_theme_supports( 'align-wide' ),
				'dark_mode'        => true,
			],
			'header' => [
				'layout'          => 'Top bar + Brand section (logo + ad)',
				'components'      => [
					'Main bar'       => 'Green top bar with left/right action buttons (nav toggle, search, dark mode)',
					'Brand section'  => 'Logo/header image on left, Ad 1 on right in responsive flex',
				],
			],
			'footer' => [
				'layout'          => 'Top widget area + Footer widgets row + Bottom bar',
				'components'      => [
					'Top'            => 'Social icons (left) + Footer menu (right)',
					'Widget row'     => 'Up to 4 footer widget columns',
					'Bottom bar'     => 'Copyright with site link, "Powered by NovaCore", and tagline',
				],
			],
			'layouts' => [
				'home'            => 'Float-based layout: .novacore-layout--home (65%) + .novacore-sidebar-area (34%)',
				'single'          => 'Standard WordPress loop with sidebar',
				'page'            => 'Full-width or with sidebar via template',
				'full_width'      => 'No sidebar, max-width 1280px centered',
			],
			'sidebars'        => $sidebars,
			'nav_menus'       => $menus,
			'custom_blocks'   => $blocks,
			'ad_placements'   => [
				'Ad 1'            => 'Header brand section (right side)',
				'Ad 2'            => 'Below slideshow on home page',
				'Ad 3'            => 'Single post top (before content)',
				'Ad 4'            => 'Single post middle (midpoint of content)',
				'Ad 5'            => 'Single post bottom (after content)',
				'Ad 6'            => 'Footer top',
			],
			'customizer_panels' => [
				'NovaCore Ads'    => 'Ad code textareas for 6 ad placements',
				'Footer Layout'   => 'Copyright tagline, footer widget columns',
				'Header Image'    => 'Custom header image (175x100) replacing site name',
			],
		];
	}

	private function set_imported_demo( string $demo ): void {
		update_option( 'novacore_imported_demo', $demo );
	}
}
