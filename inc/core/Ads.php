<?php
/**
 * NovaCore Ads Management
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Ads {

	public function __construct() {
		add_filter( 'the_content', [ $this, 'inject_content_ads' ], 20 );
	}

	public function inject_content_ads( string $content ): string {
		if ( ! is_singular( 'post' ) || ! is_main_query() ) {
			return $content;
		}

		$paragraphs = explode( '</p>', $content );
		if ( count( $paragraphs ) < 3 ) {
			return $content;
		}

		$paragraph_count = (int) get_theme_mod( 'novacore_content_ad_paragraph', 0 );
		if ( $paragraph_count > 0 ) {
			$ad_code = get_theme_mod( 'novacore_content_ad_code', '' );
			if ( ! empty( $ad_code ) && count( $paragraphs ) > $paragraph_count ) {
				$ad_markup = '</p><div class="novacore-content-ad">' . wp_kses_post( $ad_code ) . '</div><p>';
				array_splice( $paragraphs, $paragraph_count, 0, [ $ad_markup ] );
			}
		}

		$ad_4_code = get_theme_mod( 'novacore_ad_4_code', '' );
		if ( ! empty( $ad_4_code ) ) {
			$middle    = (int) ceil( count( $paragraphs ) / 2 );
			$ad_markup = '</p><div class="novacore-content-ad novacore-content-ad--middle">' . wp_kses_post( $ad_4_code ) . '</div><p>';
			array_splice( $paragraphs, $middle, 0, [ $ad_markup ] );
		}

		return implode( '</p>', $paragraphs );
	}

	public function shortcode( $atts, $content = '' ): string {
		$atts = shortcode_atts(
			[
				'area' => '',
			],
			$atts,
			'novacore_ad'
		);

		if ( ! empty( $atts['area'] ) && is_active_sidebar( "ad-{$atts['area']}" ) ) {
			ob_start();
			dynamic_sidebar( "ad-{$atts['area']}" );
			return ob_get_clean();
		}

		if ( ! empty( $content ) ) {
			return '<div class="novacore-ad">' . wp_kses_post( $content ) . '</div>';
		}

		return '';
	}
}
