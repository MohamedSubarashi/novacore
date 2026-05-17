<?php
/**
 * NovaCore SEO Module
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class SEO {

	public function __construct() {
		add_action( 'wp_head', [ $this, 'add_schema_org' ], 1 );
		add_action( 'wp_head', [ $this, 'add_open_graph' ], 2 );
		add_action( 'wp_head', [ $this, 'add_twitter_cards' ], 3 );
		add_action( 'wp_head', [ $this, 'add_meta_tags' ], 4 );
		add_filter( 'document_title_parts', [ $this, 'enhance_title' ] );
		add_filter( 'the_content', [ $this, 'add_schema_article_body' ] );
		add_filter( 'language_attributes', [ $this, 'add_og_namespace' ] );
	}

	public function add_schema_org(): void {
		if ( is_singular() ) {
			$post = get_post();
			$schema = [
				'@context'    => 'https://schema.org',
				'@type'       => 'Article',
				'headline'    => get_the_title(),
				'datePublished' => get_the_date( 'c' ),
				'dateModified'  => get_the_modified_date( 'c' ),
				'author'      => [
					'@type' => 'Person',
					'name'  => get_the_author(),
				],
			];

			if ( has_post_thumbnail() ) {
				$schema['image'] = get_the_post_thumbnail_url( null, 'full' );
			}

			$schema = apply_filters( 'novacore_schema_article', $schema, $post );
			printf(
				'<script type="application/ld+json">%s</script>',
				wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			);
		}

		if ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( get_the_ID() );
			if ( $product ) {
				$schema = [
					'@context' => 'https://schema.org',
					'@type'    => 'Product',
					'name'     => $product->get_name(),
					'description' => wp_trim_words( $product->get_short_description(), 20 ),
					'sku'      => $product->get_sku(),
				];

				if ( $product->get_price() ) {
					$schema['offers'] = [
						'@type'         => 'Offer',
						'price'         => $product->get_price(),
						'priceCurrency' => get_woocommerce_currency(),
						'availability'  => $product->is_in_stock()
							? 'https://schema.org/InStock'
							: 'https://schema.org/OutOfStock',
					];
				}

				printf(
					'<script type="application/ld+json">%s</script>',
					wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
				);
			}
		}

		if ( is_front_page() ) {
			$schema = [
				'@context'    => 'https://schema.org',
				'@type'       => 'WebSite',
				'name'        => get_bloginfo( 'name' ),
				'description' => get_bloginfo( 'description' ),
				'url'         => home_url(),
			];
			printf(
				'<script type="application/ld+json">%s</script>',
				wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			);
		}

		// BreadcrumbList schema
		if ( ! is_front_page() ) {
			$breadcrumbs = $this->get_breadcrumb_list();
			if ( ! empty( $breadcrumbs ) ) {
				printf(
					'<script type="application/ld+json">%s</script>',
					wp_json_encode( $breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
				);
			}
		}
	}

	public function add_open_graph(): void {
		if ( is_singular() ) {
			$og = [
				'og:title'       => get_the_title(),
				'og:description' => wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ),
				'og:type'        => 'article',
				'og:url'         => get_permalink(),
				'og:site_name'   => get_bloginfo( 'name' ),
				'og:locale'      => get_locale(),
			];

			if ( has_post_thumbnail() ) {
				$og['og:image']        = get_the_post_thumbnail_url( null, 'full' );
				$og['og:image:width']  = 1200;
				$og['og:image:height'] = 630;
			} elseif ( $custom_logo = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) ) {
				$og['og:image'] = $custom_logo;
			}
		} elseif ( is_front_page() || is_home() ) {
			$og = [
				'og:title'       => get_bloginfo( 'name' ),
				'og:description' => get_bloginfo( 'description' ),
				'og:type'        => 'website',
				'og:url'         => home_url(),
				'og:site_name'   => get_bloginfo( 'name' ),
			];
		} else {
			return;
		}

		$og = apply_filters( 'novacore_open_graph', $og );

		foreach ( $og as $property => $content ) {
			printf(
				'<meta property="%s" content="%s">',
				esc_attr( $property ),
				esc_attr( $content )
			);
		}
	}

	public function add_twitter_cards(): void {
		$card_type = apply_filters( 'novacore_twitter_card_type', 'summary_large_image' );
		printf( '<meta name="twitter:card" content="%s">', esc_attr( $card_type ) );
		printf( '<meta name="twitter:site" content="%s">', esc_attr( get_bloginfo( 'name' ) ) );

		if ( is_singular() ) {
			printf(
				'<meta name="twitter:title" content="%s">',
				esc_attr( get_the_title() )
			);
			printf(
				'<meta name="twitter:description" content="%s">',
				esc_attr( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) )
			);

			if ( has_post_thumbnail() ) {
				printf(
					'<meta name="twitter:image" content="%s">',
					esc_url( get_the_post_thumbnail_url( null, 'full' ) )
				);
			}
		}
	}

	public function add_meta_tags(): void {
		$description = '';

		if ( is_singular() ) {
			$description = wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 );
		} elseif ( is_front_page() || is_home() ) {
			$description = get_bloginfo( 'description' );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$description = wp_strip_all_tags( term_description() );
		}

		if ( $description ) {
			printf(
				'<meta name="description" content="%s">',
				esc_attr( $description )
			);
		}
	}

	public function enhance_title( array $title ): array {
		if ( is_singular() ) {
			$title['title'] = $this->add_paged_suffix( $title['title'] ?? '' );
		}
		if ( is_archive() && ! isset( $title['site'] ) ) {
			$title['site'] = get_bloginfo( 'name' );
		}
		return $title;
	}

	private function add_paged_suffix( string $title_text ): string {
		$page = get_query_var( 'paged' );
		if ( $page > 1 ) {
			$title_text .= sprintf(
				' | %s %d',
				esc_html__( 'Page', 'novacore' ),
				$page
			);
		}
		return $title_text;
	}

	public function add_schema_article_body( string $content ): string {
		if ( is_singular( 'post' ) && in_the_loop() && is_main_query() ) {
			$content = sprintf(
				'<article itemscope itemtype="https://schema.org/Article">%s</article>',
				$content
			);
		}
		return $content;
	}

	public function add_og_namespace( string $output ): string {
		if ( is_singular() || is_front_page() ) {
			$output .= ' prefix="og: https://ogp.me/ns#"';
		}
		return $output;
	}

	private function get_breadcrumb_list(): array {
		$items   = [];
		$items[] = [
			'@type' => 'ListItem',
			'position' => 1,
			'name'     => get_bloginfo( 'name' ),
			'item'     => home_url(),
		];

		if ( is_single() ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$items[] = [
					'@type' => 'ListItem',
					'position' => 2,
					'name' => $categories[0]->name,
					'item' => get_category_link( $categories[0]->term_id ),
				];
			}
			$items[] = [
				'@type' => 'ListItem',
				'position' => count( $items ) + 1,
				'name' => get_the_title(),
			];
		} elseif ( is_page() ) {
			$items[] = [
				'@type' => 'ListItem',
				'position' => 2,
				'name' => get_the_title(),
			];
		} elseif ( is_category() ) {
			$items[] = [
				'@type' => 'ListItem',
				'position' => 2,
				'name' => single_cat_title( '', false ),
			];
		} else {
			return [];
		}

		return [
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		];
	}
}
