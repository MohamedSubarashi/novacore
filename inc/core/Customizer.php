<?php
/**
 * NovaCore Customizer Base
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

use WP_Customize_Manager;

defined( 'ABSPATH' ) || exit;

class Customizer {

	public function __construct() {
		add_action( 'customize_register', [ $this, 'register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_js' ] );
	}

	public function register( WP_Customize_Manager $wp_customize ): void {
		$this->add_panels( $wp_customize );
		$this->add_sections( $wp_customize );
		$this->add_controls( $wp_customize );
	}

	private function add_panels( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_panel( 'novacore_general', [
			'title'    => esc_html__( 'NovaCore General', 'novacore' ),
			'priority' => 30,
		] );

		$wp_customize->add_panel( 'novacore_header', [
			'title'    => esc_html__( 'NovaCore Header', 'novacore' ),
			'priority' => 31,
		] );

		$wp_customize->add_panel( 'novacore_footer', [
			'title'    => esc_html__( 'NovaCore Footer', 'novacore' ),
			'priority' => 32,
		] );

		$wp_customize->add_panel( 'novacore_blog', [
			'title'    => esc_html__( 'NovaCore Blog', 'novacore' ),
			'priority' => 33,
		] );

		$wp_customize->add_panel( 'novacore_performance', [
			'title'    => esc_html__( 'NovaCore Performance', 'novacore' ),
			'priority' => 34,
		] );

		$wp_customize->add_panel( 'novacore_ads', [
			'title'    => esc_html__( 'NovaCore Ads', 'novacore' ),
			'priority' => 35,
		] );

		$wp_customize->add_panel( 'novacore_social', [
			'title'    => esc_html__( 'NovaCore Social', 'novacore' ),
			'description' => esc_html__( 'Manage your social media profile links.', 'novacore' ),
			'priority' => 36,
		] );

		$wp_customize->add_panel( 'novacore_feature_grids', [
			'title'    => esc_html__( 'NovaCore Feature Grids', 'novacore' ),
			'description' => esc_html__( 'Manage the multi-column feature sections displayed on the homepage and footer.', 'novacore' ),
			'priority' => 37,
		] );

		do_action( 'novacore_customizer_panels', $wp_customize );
	}

	private function add_sections( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_section( 'novacore_colors', [
			'title'    => esc_html__( 'Colors', 'novacore' ),
			'panel'    => 'novacore_general',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_typography', [
			'title'    => esc_html__( 'Typography', 'novacore' ),
			'panel'    => 'novacore_general',
			'priority' => 20,
		] );

		$wp_customize->add_section( 'novacore_layout', [
			'title'    => esc_html__( 'Layout', 'novacore' ),
			'panel'    => 'novacore_general',
			'priority' => 30,
		] );

		$wp_customize->add_section( 'novacore_dark_mode', [
			'title'    => esc_html__( 'Dark Mode', 'novacore' ),
			'panel'    => 'novacore_general',
			'priority' => 40,
		] );

		$wp_customize->add_section( 'novacore_header_layout', [
			'title'    => esc_html__( 'Header Layout', 'novacore' ),
			'panel'    => 'novacore_header',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_header_elements', [
			'title'    => esc_html__( 'Header Elements', 'novacore' ),
			'panel'    => 'novacore_header',
			'priority' => 20,
		] );

		$wp_customize->add_section( 'novacore_footer_layout', [
			'title'    => esc_html__( 'Footer Layout', 'novacore' ),
			'panel'    => 'novacore_footer',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_slideshow', [
			'title'    => esc_html__( 'Homepage Slideshow', 'novacore' ),
			'panel'    => 'novacore_blog',
			'priority' => 5,
		] );

		$wp_customize->add_section( 'novacore_blog_layout', [
			'title'    => esc_html__( 'Blog Layout', 'novacore' ),
			'panel'    => 'novacore_blog',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_blog_single', [
			'title'    => esc_html__( 'Single Post', 'novacore' ),
			'panel'    => 'novacore_blog',
			'priority' => 20,
		] );

		$wp_customize->add_section( 'novacore_mag_grid_1', [
			'title'    => esc_html__( 'Magazine Grid #1', 'novacore' ),
			'panel'    => 'novacore_blog',
			'priority' => 30,
		] );

		$wp_customize->add_section( 'novacore_mag_grid_2', [
			'title'    => esc_html__( 'Magazine Grid #2', 'novacore' ),
			'panel'    => 'novacore_blog',
			'priority' => 40,
		] );

		$wp_customize->add_section( 'novacore_performance_assets', [
			'title'    => esc_html__( 'Asset Optimization', 'novacore' ),
			'panel'    => 'novacore_performance',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_ad_placements', [
			'title'    => esc_html__( 'Ad Placements', 'novacore' ),
			'panel'    => 'novacore_ads',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_ad_1', [
			'title'    => esc_html__( 'Ad 1', 'novacore' ),
			'panel'    => 'novacore_ads',
			'priority' => 20,
		] );

		$wp_customize->add_section( 'novacore_ad_posts', [
			'title'    => esc_html__( 'Post Ads', 'novacore' ),
			'panel'    => 'novacore_ads',
			'priority' => 30,
		] );

		$wp_customize->add_section( 'novacore_social_media', [
			'title'    => esc_html__( 'Social Media Links', 'novacore' ),
			'panel'    => 'novacore_social',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_feature_grid_home', [
			'title'    => esc_html__( 'Homepage Grid', 'novacore' ),
			'panel'    => 'novacore_feature_grids',
			'priority' => 10,
		] );

		$wp_customize->add_section( 'novacore_feature_grid_footer', [
			'title'    => esc_html__( 'Footer Grid', 'novacore' ),
			'panel'    => 'novacore_feature_grids',
			'priority' => 20,
		] );

		do_action( 'novacore_customizer_sections', $wp_customize );
	}

	private function add_controls( WP_Customize_Manager $wp_customize ): void {
		$this->add_general_controls( $wp_customize );
		$this->add_header_controls( $wp_customize );
		$this->add_footer_controls( $wp_customize );
		$this->add_blog_controls( $wp_customize );
		$this->add_performance_controls( $wp_customize );
		$this->add_ad_controls( $wp_customize );
		$this->add_social_controls( $wp_customize );
		$this->add_feature_grid_controls( $wp_customize );
	}

	private function add_general_controls( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_setting( 'novacore_primary_color', [
			'default'           => '#16a34a',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'novacore_primary_color', [
			'label'   => esc_html__( 'Primary Color', 'novacore' ),
			'section' => 'novacore_colors',
		] ) );

		$wp_customize->add_setting( 'novacore_secondary_color', [
			'default'           => '#3b82f6',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'novacore_secondary_color', [
			'label'   => esc_html__( 'Secondary Color', 'novacore' ),
			'section' => 'novacore_colors',
		] ) );

		$wp_customize->add_setting( 'novacore_accent_color', [
			'default'           => '#3b82f6',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'novacore_accent_color', [
			'label'   => esc_html__( 'Accent Color', 'novacore' ),
			'section' => 'novacore_colors',
		] ) );

		$wp_customize->add_setting( 'novacore_container_width', [
			'default'           => 1280,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_container_width', [
			'label'       => esc_html__( 'Container Width (px)', 'novacore' ),
			'section'     => 'novacore_layout',
			'type'        => 'number',
			'input_attrs' => [
				'min'  => 800,
				'max'  => 1920,
				'step' => 10,
			],
		] );

		$wp_customize->add_setting( 'novacore_site_layout', [
			'default'           => 'wide',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_site_layout', [
			'label'   => esc_html__( 'Site Layout', 'novacore' ),
			'section' => 'novacore_layout',
			'type'    => 'select',
			'choices' => [
				'wide'     => esc_html__( 'Wide', 'novacore' ),
				'boxed'    => esc_html__( 'Boxed', 'novacore' ),
				'framed'   => esc_html__( 'Framed', 'novacore' ),
			],
		] );

		$wp_customize->add_setting( 'novacore_dark_mode', [
			'default'           => 'system',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		] );

		$wp_customize->add_control( 'novacore_dark_mode', [
			'label'   => esc_html__( 'Dark Mode', 'novacore' ),
			'section' => 'novacore_dark_mode',
			'type'    => 'select',
			'choices' => [
				'disabled' => esc_html__( 'Disabled', 'novacore' ),
				'light'    => esc_html__( 'Force Light', 'novacore' ),
				'dark'     => esc_html__( 'Force Dark', 'novacore' ),
				'system'   => esc_html__( 'System Preference', 'novacore' ),
			],
		] );

		$wp_customize->add_setting( 'novacore_body_font', [
			'default'           => 'system',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_body_font', [
			'label'   => esc_html__( 'Body Font', 'novacore' ),
			'section' => 'novacore_typography',
			'type'    => 'select',
			'choices' => [
				'system'   => esc_html__( 'System Stack', 'novacore' ),
				'inter'    => 'Inter',
				'roboto'   => 'Roboto',
				'poppins'  => 'Poppins',
				'nunito'   => 'Nunito',
				'opensans' => 'Open Sans',
			],
		] );

		$wp_customize->add_setting( 'novacore_heading_font', [
			'default'           => 'system',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_heading_font', [
			'label'   => esc_html__( 'Heading Font', 'novacore' ),
			'section' => 'novacore_typography',
			'type'    => 'select',
			'choices' => [
				'system'   => esc_html__( 'System Stack', 'novacore' ),
				'inter'    => 'Inter',
				'roboto'   => 'Roboto',
				'poppins'  => 'Poppins',
				'nunito'   => 'Nunito',
				'opensans' => 'Open Sans',
			],
		] );

		$wp_customize->add_setting( 'novacore_body_font_size', [
			'default'           => 16,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_body_font_size', [
			'label'       => esc_html__( 'Body Font Size (px)', 'novacore' ),
			'section'     => 'novacore_typography',
			'type'        => 'number',
			'input_attrs' => [ 'min' => 12, 'max' => 24, 'step' => 1 ],
		] );

		do_action( 'novacore_customizer_general_controls', $wp_customize );
	}

	private function add_header_controls( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_setting( 'novacore_sticky_header', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_sticky_header', [
			'label'   => esc_html__( 'Sticky Header', 'novacore' ),
			'section' => 'novacore_header_layout',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_transparent_header', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_transparent_header', [
			'label'   => esc_html__( 'Transparent Header', 'novacore' ),
			'section' => 'novacore_header_layout',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_show_search', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_show_search', [
			'label'   => esc_html__( 'Show Search Icon', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_show_cart', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_show_cart', [
			'label'   => esc_html__( 'Show Cart Icon', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_header_cta_text', [
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_header_cta_text', [
			'label'   => esc_html__( 'CTA Button Text', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'text',
		] );

		$wp_customize->add_setting( 'novacore_header_cta_url', [
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_header_cta_url', [
			'label'   => esc_html__( 'CTA Button URL', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'url',
		] );

		$wp_customize->add_setting( 'novacore_header_cta_style', [
			'default'           => 'primary',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_header_cta_style', [
			'label'   => esc_html__( 'CTA Button Style', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'select',
			'choices' => [
				'primary'   => esc_html__( 'Primary', 'novacore' ),
				'secondary' => esc_html__( 'Secondary', 'novacore' ),
				'outline'   => esc_html__( 'Outline', 'novacore' ),
			],
		] );

		$wp_customize->add_setting( 'novacore_animations', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_animations', [
			'label'   => esc_html__( 'Scroll Animations', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_home_link_text', [
			'default'           => esc_html__( 'Home', 'novacore' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_home_link_text', [
			'label'   => esc_html__( 'Home Link Text', 'novacore' ),
			'section' => 'novacore_header_elements',
			'type'    => 'text',
		] );

		do_action( 'novacore_customizer_header_controls', $wp_customize );
	}

	private function add_footer_controls( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_setting( 'novacore_footer_columns', [
			'default'           => 3,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_footer_columns', [
			'label'   => esc_html__( 'Footer Columns', 'novacore' ),
			'section' => 'novacore_footer_layout',
			'type'    => 'select',
			'choices' => [
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			],
		] );

		$wp_customize->add_setting( 'novacore_copyright_tagline', [
			'default'           => esc_html__( 'All rights reserved', 'novacore' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_copyright_tagline', [
			'label'   => esc_html__( 'Copyright Tagline', 'novacore' ),
			'section' => 'novacore_footer_layout',
			'type'    => 'text',
		] );

		$wp_customize->add_setting( 'novacore_footer_show_newsletter', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_footer_show_newsletter', [
			'label'   => esc_html__( 'Show Newsletter Section', 'novacore' ),
			'section' => 'novacore_footer_layout',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_newsletter_title', [
			'default'           => esc_html__( 'Stay Updated', 'novacore' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_newsletter_title', [
			'label'   => esc_html__( 'Newsletter Title', 'novacore' ),
			'section' => 'novacore_footer_layout',
			'type'    => 'text',
		] );

		$wp_customize->add_setting( 'novacore_newsletter_text', [
			'default'           => esc_html__( 'Subscribe to our newsletter for the latest updates.', 'novacore' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_newsletter_text', [
			'label'   => esc_html__( 'Newsletter Description', 'novacore' ),
			'section' => 'novacore_footer_layout',
			'type'    => 'textarea',
		] );

		$wp_customize->add_setting( 'novacore_footer_menu_links', [
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_footer_menu_links', [
			'label'       => esc_html__( 'Footer Menu Links', 'novacore' ),
			'description' => esc_html__( 'Custom links for the footer. One per line. Format: Label|URL. Leave empty to use the WordPress Footer Menu instead.', 'novacore' ),
			'section'     => 'novacore_footer_layout',
			'type'        => 'textarea',
			'input_attrs' => [
				'placeholder' => "Privacy Policy|https://yoursite.com/privacy\nTerms of Service|https://yoursite.com/terms",
			],
		] );

		do_action( 'novacore_customizer_footer_controls', $wp_customize );
	}

	private function add_blog_controls( WP_Customize_Manager $wp_customize ): void {
		$this->add_slideshow_controls( $wp_customize );

		$wp_customize->add_setting( 'novacore_blog_layout', [
			'default'           => 'grid',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_blog_layout', [
			'label'   => esc_html__( 'Blog Layout', 'novacore' ),
			'section' => 'novacore_blog_layout',
			'type'    => 'select',
			'choices' => [
				'grid'    => esc_html__( 'Grid', 'novacore' ),
				'list'    => esc_html__( 'List', 'novacore' ),
				'masonry' => esc_html__( 'Masonry', 'novacore' ),
				'magazine' => esc_html__( 'Magazine', 'novacore' ),
			],
		] );

		$wp_customize->add_setting( 'novacore_blog_columns', [
			'default'           => 2,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_blog_columns', [
			'label'   => esc_html__( 'Blog Columns', 'novacore' ),
			'section' => 'novacore_blog_layout',
			'type'    => 'select',
			'choices' => [
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			],
		] );

		$wp_customize->add_setting( 'novacore_blog_infinite_scroll', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_blog_infinite_scroll', [
			'label'   => esc_html__( 'Infinite Scroll', 'novacore' ),
			'section' => 'novacore_blog_layout',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_show_related_posts', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_show_related_posts', [
			'label'   => esc_html__( 'Show Related Posts', 'novacore' ),
			'section' => 'novacore_blog_single',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_show_author_box', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_show_author_box', [
			'label'   => esc_html__( 'Show Author Box', 'novacore' ),
			'section' => 'novacore_blog_single',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_show_reading_progress', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_show_reading_progress', [
			'label'   => esc_html__( 'Show Reading Progress Bar', 'novacore' ),
			'section' => 'novacore_blog_single',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_ajax_pagination', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ajax_pagination', [
			'label'   => esc_html__( 'AJAX Pagination', 'novacore' ),
			'section' => 'novacore_blog_layout',
			'type'    => 'checkbox',
		] );

		$mag_grids = [
			'1' => [
				'section' => 'novacore_mag_grid_1',
				'default_title' => esc_html__( 'Editor\'s Pick', 'novacore' ),
			],
			'2' => [
				'section' => 'novacore_mag_grid_2',
				'default_title' => esc_html__( 'Must Read', 'novacore' ),
			],
		];

		foreach ( $mag_grids as $num => $cfg ) {
			$show_key  = "novacore_mag_grid_{$num}_show";
			$title_key = "novacore_mag_grid_{$num}_title";
			$count_key = "novacore_mag_grid_{$num}_count";

			$wp_customize->add_setting( $show_key, [
				'default'           => true,
				'sanitize_callback' => 'novacore_sanitize_checkbox',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $show_key, [
				'label'   => esc_html__( 'Show this grid', 'novacore' ),
				'section' => $cfg['section'],
				'type'    => 'checkbox',
			] );

			$wp_customize->add_setting( $title_key, [
				'default'           => $cfg['default_title'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $title_key, [
				'label'   => esc_html__( 'Section Title', 'novacore' ),
				'section' => $cfg['section'],
				'type'    => 'text',
			] );

			$wp_customize->add_setting( $count_key, [
				'default'           => 3,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $count_key, [
				'label'   => esc_html__( 'Number of posts', 'novacore' ),
				'section' => $cfg['section'],
				'type'    => 'select',
				'choices' => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
			] );
		}

		do_action( 'novacore_customizer_blog_controls', $wp_customize );
	}

	private function add_performance_controls( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_setting( 'novacore_minify_css', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_minify_css', [
			'label'   => esc_html__( 'Minify CSS', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_minify_js', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_minify_js', [
			'label'   => esc_html__( 'Minify JavaScript', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_lazy_load_images', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_lazy_load_images', [
			'label'   => esc_html__( 'Lazy Load Images', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_local_fonts', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_local_fonts', [
			'label'   => esc_html__( 'Serve Google Fonts Locally', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_remove_emoji', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_remove_emoji', [
			'label'   => esc_html__( 'Remove Emoji Scripts', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_remove_wp_block_library', [
			'default'           => false,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_remove_wp_block_library', [
			'label'   => esc_html__( 'Remove Block Library CSS', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_move_jquery_to_footer', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_move_jquery_to_footer', [
			'label'   => esc_html__( 'Move jQuery to Footer', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_webp_support', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_webp_support', [
			'label'   => esc_html__( 'WebP Support', 'novacore' ),
			'section' => 'novacore_performance_assets',
			'type'    => 'checkbox',
		] );

		do_action( 'novacore_customizer_performance_controls', $wp_customize );
	}

	private function add_ad_controls( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_setting( 'novacore_content_ad_paragraph', [
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_content_ad_paragraph', [
			'label'       => esc_html__( 'Insert ad after paragraph #', 'novacore' ),
			'description' => esc_html__( 'Set to 0 to disable auto-insertion.', 'novacore' ),
			'section'     => 'novacore_ad_placements',
			'type'        => 'number',
			'input_attrs' => [ 'min' => 0, 'max' => 50, 'step' => 1 ],
		] );

		$wp_customize->add_setting( 'novacore_content_ad_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_content_ad_code', [
			'label'       => esc_html__( 'In-Content Ad Code', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code inserted into single post content.', 'novacore' ),
			'section'     => 'novacore_ad_placements',
			'type'        => 'textarea',
		] );

		$wp_customize->add_setting( 'novacore_ad_spacing', [
			'default'           => 20,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_spacing', [
			'label'       => esc_html__( 'Ad Area Spacing (px)', 'novacore' ),
			'section'     => 'novacore_ad_placements',
			'type'        => 'number',
			'input_attrs' => [ 'min' => 0, 'max' => 100, 'step' => 5 ],
		] );

		do_action( 'novacore_customizer_ad_controls', $wp_customize );

		// Ad 1: Header Brand
		$wp_customize->add_setting( 'novacore_ad_1_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_1_code', [
			'label'       => esc_html__( 'Ad 1: Header Brand (728×90)', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code displayed beside the logo.', 'novacore' ),
			'section'     => 'novacore_ad_1',
			'type'        => 'textarea',
		] );

		// Ad 2: Below Slideshow
		$wp_customize->add_setting( 'novacore_ad_2_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_2_code', [
			'label'       => esc_html__( 'Ad 2: Below Slideshow', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code below the featured slider.', 'novacore' ),
			'section'     => 'novacore_ad_1',
			'type'        => 'textarea',
		] );

		// Ad 3: Post Top
		$wp_customize->add_setting( 'novacore_ad_3_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_3_code', [
			'label'       => esc_html__( 'Ad 3: Post Top', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code at the top of the post content.', 'novacore' ),
			'section'     => 'novacore_ad_posts',
			'type'        => 'textarea',
		] );

		// Ad 4: Post Middle
		$wp_customize->add_setting( 'novacore_ad_4_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_4_code', [
			'label'       => esc_html__( 'Ad 4: Post Middle', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code in the middle of the post content.', 'novacore' ),
			'section'     => 'novacore_ad_posts',
			'type'        => 'textarea',
		] );

		// Ad 5: Post Bottom
		$wp_customize->add_setting( 'novacore_ad_5_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_5_code', [
			'label'       => esc_html__( 'Ad 5: Post Bottom', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code at the bottom of the post content.', 'novacore' ),
			'section'     => 'novacore_ad_posts',
			'type'        => 'textarea',
		] );

		// Ad 6: Footer Top
		$wp_customize->add_setting( 'novacore_ad_6_code', [
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_ad_6_code', [
			'label'       => esc_html__( 'Ad 6: Footer Top', 'novacore' ),
			'description' => esc_html__( 'HTML or AdSense code at the top of the footer.', 'novacore' ),
			'section'     => 'novacore_ad_1',
			'type'        => 'textarea',
		] );
	}

	private function add_social_controls( WP_Customize_Manager $wp_customize ): void {
		$social_networks = [
			'facebook'   => esc_html__( 'Facebook', 'novacore' ),
			'twitter'    => esc_html__( 'Twitter / X', 'novacore' ),
			'instagram'  => esc_html__( 'Instagram', 'novacore' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'novacore' ),
			'youtube'    => esc_html__( 'YouTube', 'novacore' ),
			'tiktok'     => esc_html__( 'TikTok', 'novacore' ),
			'snapchat'   => esc_html__( 'Snapchat', 'novacore' ),
			'pinterest'  => esc_html__( 'Pinterest', 'novacore' ),
			'whatsapp'   => esc_html__( 'WhatsApp', 'novacore' ),
			'telegram'   => esc_html__( 'Telegram', 'novacore' ),
			'discord'    => esc_html__( 'Discord', 'novacore' ),
			'twitch'     => esc_html__( 'Twitch', 'novacore' ),
			'reddit'     => esc_html__( 'Reddit', 'novacore' ),
			'medium'     => esc_html__( 'Medium', 'novacore' ),
			'tumblr'     => esc_html__( 'Tumblr', 'novacore' ),
			'vimeo'      => esc_html__( 'Vimeo', 'novacore' ),
			'dribbble'   => esc_html__( 'Dribbble', 'novacore' ),
			'behance'    => esc_html__( 'Behance', 'novacore' ),
			'soundcloud' => esc_html__( 'SoundCloud', 'novacore' ),
			'spotify'    => esc_html__( 'Spotify', 'novacore' ),
			'threads'    => esc_html__( 'Threads', 'novacore' ),
			'bluesky'    => esc_html__( 'Bluesky', 'novacore' ),
			'mastodon'   => esc_html__( 'Mastodon', 'novacore' ),
			'flickr'     => esc_html__( 'Flickr', 'novacore' ),
			'deviantart' => esc_html__( 'DeviantArt', 'novacore' ),
			'github'     => esc_html__( 'GitHub', 'novacore' ),
			'codepen'    => esc_html__( 'CodePen', 'novacore' ),
			'stackoverflow' => esc_html__( 'Stack Overflow', 'novacore' ),
			'patreon'    => esc_html__( 'Patreon', 'novacore' ),
			'etsy'       => esc_html__( 'Etsy', 'novacore' ),
			'bandcamp'   => esc_html__( 'Bandcamp', 'novacore' ),
			'slack'      => esc_html__( 'Slack', 'novacore' ),
			'xbox'       => esc_html__( 'Xbox', 'novacore' ),
			'playstation' => esc_html__( 'PlayStation', 'novacore' ),
			'steam'      => esc_html__( 'Steam', 'novacore' ),
			'vk'         => esc_html__( 'VK', 'novacore' ),
			'weibo'      => esc_html__( 'Weibo', 'novacore' ),
			'line'       => esc_html__( 'LINE', 'novacore' ),
			'goodreads'  => esc_html__( 'Goodreads', 'novacore' ),
			'quora'      => esc_html__( 'Quora', 'novacore' ),
			'imdb'       => esc_html__( 'IMDb', 'novacore' ),
		];

		foreach ( $social_networks as $network => $label ) {
			$wp_customize->add_setting( "novacore_social_{$network}", [
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			] );

			$wp_customize->add_control( "novacore_social_{$network}", [
				'label'   => sprintf(
					esc_html__( '%s URL', 'novacore' ),
					$label
				),
				'section' => 'novacore_social_media',
				'type'    => 'url',
				'input_attrs' => [
					'placeholder' => 'https://',
				],
			] );
		}

		$wp_customize->add_setting( 'novacore_social_custom', [
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_social_custom', [
			'label'       => esc_html__( 'Custom Social Links', 'novacore' ),
			'description' => esc_html__( 'Add any Font Awesome brand icon. One per line. Format: fa-icon-class|Label|URL. Browse all icons at https://fontawesome.com/search?m=free&s=brands', 'novacore' ),
			'section'     => 'novacore_social_media',
			'type'        => 'textarea',
			'input_attrs' => [
				'placeholder' => "fa-brands fa-telegram|Telegram|https://t.me/username\nfa-brands fa-discord|Discord|https://discord.gg/invite",
			],
		] );

		do_action( 'novacore_customizer_social_controls', $wp_customize );
	}

	private function add_feature_grid_controls( WP_Customize_Manager $wp_customize ): void {
		$locations = [
			'home'   => [
				'section'     => 'novacore_feature_grid_home',
				'title_label' => esc_html__( 'Homepage Grid Title', 'novacore' ),
				'items_label' => esc_html__( 'Homepage Grid Items', 'novacore' ),
				'items_desc'  => esc_html__( 'One per line. Format: fa-icon-class|Title|Description', 'novacore' ),
				'items_placeholder' => "fa-solid fa-newspaper|Breaking News|Stay informed with real-time updates.\nfa-solid fa-chart-line|Market Analysis|Expert insights on global markets.",
			],
			'footer' => [
				'section'     => 'novacore_feature_grid_footer',
				'title_label' => esc_html__( 'Footer Grid Title', 'novacore' ),
				'items_label' => esc_html__( 'Footer Grid Items', 'novacore' ),
				'items_desc'  => esc_html__( 'One per line. Format: fa-icon-class|Title|Description', 'novacore' ),
				'items_placeholder' => "fa-solid fa-newspaper|Breaking News|Stay informed with real-time updates.\nfa-solid fa-chart-line|Market Analysis|Expert insights on global markets.",
			],
		];

		foreach ( $locations as $location => $cfg ) {
			$show_key     = "novacore_feature_grid_{$location}_show";
			$cols_key     = "novacore_feature_grid_{$location}_cols";
			$items_key    = "novacore_feature_grid_{$location}_items";

			$wp_customize->add_setting( $show_key, [
				'default'           => true,
				'sanitize_callback' => 'novacore_sanitize_checkbox',
				'transport'         => 'refresh',
			] );

			$wp_customize->add_control( $show_key, [
				'label'   => esc_html__( 'Show this grid', 'novacore' ),
				'section' => $cfg['section'],
				'type'    => 'checkbox',
			] );

			$wp_customize->add_setting( $cols_key, [
				'default'           => 4,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			] );

			$wp_customize->add_control( $cols_key, [
				'label'   => esc_html__( 'Columns', 'novacore' ),
				'section' => $cfg['section'],
				'type'    => 'select',
				'choices' => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
			] );

			$wp_customize->add_setting( $items_key, [
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			] );

			$wp_customize->add_control( $items_key, [
				'label'       => $cfg['items_label'],
				'description' => $cfg['items_desc'],
				'section'     => $cfg['section'],
				'type'        => 'textarea',
				'input_attrs' => [
					'placeholder' => $cfg['items_placeholder'],
				],
			] );
		}

		do_action( 'novacore_customizer_feature_grid_controls', $wp_customize );
	}

	private function add_slideshow_controls( WP_Customize_Manager $wp_customize ): void {
		$section = 'novacore_slideshow';

		$wp_customize->add_setting( 'novacore_slideshow_show', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_show', [
			'label'   => esc_html__( 'Show Slideshow on Homepage', 'novacore' ),
			'section' => $section,
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_slideshow_source', [
			'default'           => 'recent',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_source', [
			'label'   => esc_html__( 'Slideshow Source', 'novacore' ),
			'section' => $section,
			'type'    => 'select',
			'choices' => [
				'recent'   => esc_html__( 'Recent Posts', 'novacore' ),
				'category' => esc_html__( 'Specific Category', 'novacore' ),
			],
		] );

		$wp_customize->add_setting( 'novacore_slideshow_category', [
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_category', [
			'label'   => esc_html__( 'Category', 'novacore' ),
			'section' => $section,
			'type'    => 'select',
			'choices' => $this->get_category_choices(),
		] );

		$wp_customize->add_setting( 'novacore_slideshow_count', [
			'default'           => 5,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_count', [
			'label'       => esc_html__( 'Number of Slides', 'novacore' ),
			'section'     => $section,
			'type'        => 'number',
			'input_attrs' => [
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			],
		] );

		$wp_customize->add_setting( 'novacore_slideshow_autoplay', [
			'default'           => true,
			'sanitize_callback' => 'novacore_sanitize_checkbox',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_autoplay', [
			'label'   => esc_html__( 'Auto-play Slideshow', 'novacore' ),
			'section' => $section,
			'type'    => 'checkbox',
		] );

		$wp_customize->add_setting( 'novacore_slideshow_speed', [
			'default'           => 5000,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( 'novacore_slideshow_speed', [
			'label'       => esc_html__( 'Autoplay Speed (ms)', 'novacore' ),
			'section'     => $section,
			'type'        => 'number',
			'input_attrs' => [
				'min'  => 1000,
				'max'  => 30000,
				'step' => 500,
			],
		] );
	}

	private function get_category_choices(): array {
		$choices = [ 0 => esc_html__( '— Select —', 'novacore' ) ];
		$cats    = get_categories( [ 'hide_empty' => false ] );
		foreach ( $cats as $cat ) {
			$choices[ $cat->term_id ] = $cat->name;
		}
		return $choices;
	}

	public function preview_js(): void {
		$file = '/build/js/customizer.min.js';
		$path = NOVACORE_DIR . $file;
		$ver  = file_exists( $path ) ? filemtime( $path ) : NOVACORE_VERSION;

		wp_enqueue_script(
			'novacore-customizer',
			NOVACORE_URI . $file,
			[ 'jquery', 'customize-preview', 'customize-selective-refresh' ],
			$ver,
			true
		);
	}
}
