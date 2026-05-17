<?php
/**
 * NovaCore Performance Module
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Performance {

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function init(): void {
		if ( get_theme_mod( 'novacore_lazy_load_images', true ) ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_true' );
			add_filter( 'wp_get_attachment_image_attributes', [ $this, 'lazy_load_attributes' ], 10, 3 );
		}

		if ( get_theme_mod( 'novacore_remove_emoji', true ) ) {
			$this->disable_emojis();
		}

		add_action( 'wp_head', [ $this, 'add_dns_prefetch' ], 0 );

		if ( get_theme_mod( 'novacore_remove_wp_block_library', false ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_block_library' ], 100 );
		}

		if ( get_theme_mod( 'novacore_move_jquery_to_footer', true ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'move_jquery_to_footer' ] );
		}

		add_action( 'init', [ $this, 'add_image_sizes' ] );
		add_filter( 'wp_resource_hints', [ $this, 'add_preconnect' ], 10, 2 );

		do_action( 'novacore_performance_init' );
	}

	public function lazy_load_attributes( array $attr, \WP_Post $attachment, string $size ): array {
		if ( isset( $attr['loading'] ) && 'eager' === $attr['loading'] ) {
			return $attr;
		}
		$attr['loading'] = 'lazy';
		return $attr;
	}

	public function disable_emojis(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	public function dequeue_block_library(): void {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	public function move_jquery_to_footer(): void {
		if ( is_admin() ) {
			return;
		}
		wp_scripts()->add_data( 'jquery', 'group', 1 );
		wp_scripts()->add_data( 'jquery-core', 'group', 1 );
		wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
	}

	public function add_image_sizes(): void {
		if ( get_theme_mod( 'novacore_webp_support', true ) ) {
			add_filter( 'wp_get_attachment_image_attributes', [ $this, 'add_srcset_webp' ], 10, 3 );
		}
	}

	public function add_preconnect( array $urls, string $relation_type ): array {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = 'https://fonts.gstatic.com';
		}
		return $urls;
	}

	public function add_dns_prefetch(): void {
		echo '<meta http-equiv="x-dns-prefetch-control" content="on">';
	}

	public function add_srcset_webp( array $attr, \WP_Post $attachment, string $size ): array {
		if ( ! isset( $attr['srcset'] ) || empty( $attr['srcset'] ) ) {
			return $attr;
		}

		$srcset_parts = explode( ', ', $attr['srcset'] );
		foreach ( $srcset_parts as $i => $part ) {
			$url_parts = explode( ' ', trim( $part ) );
			if ( ! empty( $url_parts[0] ) ) {
				$webp_url = preg_replace( '/\.(jpe?g|png|gif)$/i', '.webp', $url_parts[0] );
				$webp_path = str_replace( content_url(), WP_CONTENT_DIR, $webp_url );
				if ( @file_exists( $webp_path ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					$srcset_parts[ $i ] = $webp_url . ' ' . ( $url_parts[1] ?? '' );
				}
			}
		}
		$attr['srcset'] = implode( ', ', $srcset_parts );
		return $attr;
	}
}
