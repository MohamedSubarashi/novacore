<?php
/**
 * NovaCore Gutenberg Blocks Registration
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Blocks {

	public function __construct() {
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_action( 'init', [ $this, 'register_block_styles' ] );
		add_action( 'init', [ $this, 'register_block_patterns' ] );
		add_filter( 'block_categories_all', [ $this, 'register_category' ] );
	}

	public function register_block_styles(): void {
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}

		register_block_style( 'core/paragraph', [
			'name'  => 'novacore-lead',
			'label' => __( 'Lead', 'novacore' ),
		] );
	}

	public function register_block_patterns(): void {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}

		register_block_pattern( 'novacore/hero-cta', [
			'title'       => __( 'Hero with CTA', 'novacore' ),
			'description' => __( 'A full-width hero section with heading, text, and call-to-action buttons.', 'novacore' ),
			'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"primary","textColor":"white"} --><div class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Welcome</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Get started with NovaCore today.</p><!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">Get Started</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'categories'  => [ 'novacore' ],
		] );
	}

	public function register_blocks(): void {
		if ( ! apply_filters( 'novacore_register_blocks', true ) ) {
			return;
		}

		$blocks = [
			'hero'        => 'NovaCore\Blocks\Hero',
			'pricing'     => 'NovaCore\Blocks\Pricing',
			'testimonial' => 'NovaCore\Blocks\Testimonial',
			'faq'         => 'NovaCore\Blocks\FAQ',
			'team'        => 'NovaCore\Blocks\Team',
			'feature-grid' => 'NovaCore\Blocks\Feature_Grid',
			'cta'         => 'NovaCore\Blocks\CTA',
			'post-grid'   => 'NovaCore\Blocks\Post_Grid',
			'counter'     => 'NovaCore\Blocks\Counter',
			'slider'      => 'NovaCore\Blocks\Slider',
			'buttons'     => 'NovaCore\Blocks\Buttons',
		];

		foreach ( $blocks as $name => $render_callback ) {
			$block_json = NOVACORE_BLOCKS_DIR . "/{$name}/block.json";
			if ( file_exists( $block_json ) ) {
				$args = [];
				if ( class_exists( $render_callback ) ) {
					$callback_instance = new $render_callback();
					if ( method_exists( $callback_instance, 'render' ) ) {
						$args['render_callback'] = [ $callback_instance, 'render' ];
					}
				}
				$register_fn = 'register_block_type';
				$register_fn( $block_json, $args );
			}
		}

		do_action( 'novacore_register_blocks' );
	}

	public function register_category( array $categories ): array {
		return array_merge(
			[
				[
					'slug'  => 'novacore',
					'title' => esc_html__( 'NovaCore', 'novacore' ),
				],
			],
			$categories
		);
	}
}
