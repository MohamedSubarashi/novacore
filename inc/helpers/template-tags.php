<?php
/**
 * NovaCore Template Tags
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'novacore_sanitize_checkbox' ) ) {
	function novacore_sanitize_checkbox( $checked ): bool {
		return (bool) $checked;
	}
}

if ( ! function_exists( 'novacore_get_site_logo' ) ) {
	function novacore_get_site_logo( string $class = '' ): string {
		if ( has_custom_logo() ) {
			return get_custom_logo();
		}

		return sprintf(
			'<a href="%s" class="novacore-logo %s" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			esc_attr( $class ),
			esc_html( get_bloginfo( 'name' ) )
		);
	}
}

if ( ! function_exists( 'novacore_entry_meta' ) ) {
	function novacore_entry_meta(): void {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		$posted_on = sprintf(
			'<span class="posted-on">%s</span>',
			$time_string
		);

		$byline = sprintf(
			'<span class="byline">%s <a href="%s" rel="author">%s</a></span>',
			esc_html__( 'by', 'novacore' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);

		$categories_list = get_the_category_list( ', ' );
		$categories = $categories_list
			? sprintf( '<span class="cat-links">%s</span>', $categories_list )
			: '';

		$tags_list = get_the_tag_list( '', ', ' );
		$tags = $tags_list
			? sprintf( '<span class="tags-links">%s</span>', $tags_list )
			: '';

		$comments = '';
		if ( ! post_password_required() && comments_open() ) {
			$comments = sprintf(
				'<span class="comments-link"><a href="%s">%s</a></span>',
				esc_url( get_comments_link() ),
				sprintf(
					_n( '%s Comment', '%s Comments', get_comments_number(), 'novacore' ),
					number_format_i18n( get_comments_number() )
				)
			);
		}

		echo '<div class="entry-meta">';
		echo $posted_on . ' ' . $byline;
		echo $categories . ' ' . $tags . ' ' . $comments;
		echo '</div>';
	}
}

if ( ! function_exists( 'novacore_reading_time' ) ) {
	function novacore_reading_time(): string {
		$content = get_post_field( 'post_content', get_the_ID() );
		$words   = str_word_count( wp_strip_all_tags( $content ) );
		$minutes = (int) ceil( $words / 200 );

		if ( $minutes < 1 ) {
			$minutes = 1;
		}

		return sprintf(
			_n( '%d min read', '%d min read', $minutes, 'novacore' ),
			$minutes
		);
	}
}

if ( ! function_exists( 'novacore_pagination' ) ) {
	function novacore_pagination( $custom_query = null ): void {
		if ( $custom_query ) {
			global $wp_query;
			$temp_query = $wp_query;
			$wp_query   = $custom_query;
		}

		$pagination = get_the_posts_pagination( [
			'mid_size'           => 2,
			'prev_text'          => sprintf(
				'<span aria-hidden="true">&larr;</span> %s',
				esc_html__( 'Previous', 'novacore' )
			),
			'next_text'          => sprintf(
				'%s <span aria-hidden="true">&rarr;</span>',
				esc_html__( 'Next', 'novacore' )
			),
			'screen_reader_text' => esc_html__( 'Posts navigation', 'novacore' ),
		] );

		if ( $custom_query ) {
			$wp_query = $temp_query;
		}

		if ( $pagination ) {
			echo '<nav class="novacore-pagination" role="navigation" aria-label="' . esc_attr__( 'Pagination', 'novacore' ) . '">';
			echo $pagination;
			echo '</nav>';
		}
	}
}

if ( ! function_exists( 'novacore_breadcrumbs' ) ) {
	function novacore_breadcrumbs(): void {
		if ( is_front_page() ) {
			return;
		}

		$separator = apply_filters( 'novacore_breadcrumb_separator', ' / ' );
		$home_text = apply_filters( 'novacore_breadcrumb_home_text', esc_html__( 'Home', 'novacore' ) );

		echo '<nav class="novacore-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'novacore' ) . '">';
		echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( $home_text ) . '</a>';

		if ( is_single() ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				echo esc_html( $separator );
				echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
			}
			echo esc_html( $separator );
			the_title( '<span class="current">', '</span>' );
		} elseif ( is_page() ) {
			$post = get_post();
			if ( $post->post_parent ) {
				$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
				foreach ( $ancestors as $ancestor ) {
					echo esc_html( $separator );
					echo '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
				}
			}
			echo esc_html( $separator );
			the_title( '<span class="current">', '</span>' );
		} elseif ( is_category() ) {
			echo esc_html( $separator );
			single_cat_title( '<span class="current">', '</span>' );
		} elseif ( is_tag() ) {
			echo esc_html( $separator );
			single_tag_title( '<span class="current">', '</span>' );
		} elseif ( is_author() ) {
			echo esc_html( $separator );
			echo '<span class="current">' . esc_html( get_the_author() ) . '</span>';
		} elseif ( is_search() ) {
			echo esc_html( $separator );
			printf(
				'<span class="current">%s %s</span>',
				esc_html__( 'Search results for:', 'novacore' ),
				esc_html( get_search_query() )
			);
		} elseif ( is_404() ) {
			echo esc_html( $separator );
			echo '<span class="current">' . esc_html__( '404 Not Found', 'novacore' ) . '</span>';
		} elseif ( is_archive() ) {
			echo esc_html( $separator );
			echo '<span class="current">' . esc_html__( 'Archive', 'novacore' ) . '</span>';
		}

		echo '</nav>';
	}
}

if ( ! function_exists( 'novacore_post_thumbnail' ) ) {
	function novacore_post_thumbnail( string $size = 'novacore-lg', array $attr = [] ): void {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		$default_attr = [
			'loading' => 'lazy',
			'class'   => 'novacore-thumbnail',
		];

		$attr = wp_parse_args( $attr, $default_attr );

		printf(
			'<figure class="post-thumbnail">%s</figure>',
			wp_kses_post(
				get_the_post_thumbnail( get_the_ID(), $size, $attr )
			)
		);
	}
}

if ( ! function_exists( 'novacore_entry_footer' ) ) {
	function novacore_entry_footer(): void {
		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'novacore' ),
					[ 'span' => [ 'class' => [] ] ]
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'novacore_body_classes' ) ) {
	function novacore_body_classes( array $classes ): array {
		if ( is_singular() ) {
			$classes[] = 'singular';
		}

		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'has-sidebar';
		}

		$site_layout = get_theme_mod( 'novacore_site_layout', 'wide' );
		$classes[] = 'site-layout-' . $site_layout;

		$dark_mode = get_theme_mod( 'novacore_dark_mode', 'system' );
		if ( 'dark' === $dark_mode ) {
			$classes[] = 'dark-mode';
		} elseif ( 'system' === $dark_mode ) {
			$classes[] = 'dark-mode-system';
		}

		$header_layout = get_theme_mod( 'novacore_sticky_header', true );
		if ( $header_layout ) {
			$classes[] = 'has-sticky-header';
		}

		$transparent = get_theme_mod( 'novacore_transparent_header', false );
		if ( $transparent ) {
			$classes[] = 'has-transparent-header';
		}

		$blog_layout = get_theme_mod( 'novacore_blog_layout', 'grid' );
		if ( is_home() || is_archive() || is_search() ) {
			$classes[] = 'blog-layout-' . $blog_layout;
		}

		return $classes;
	}
}
add_filter( 'body_class', 'novacore_body_classes' );

if ( ! function_exists( 'novacore_primary_menu_fallback' ) ) {
	function novacore_primary_menu_fallback(): void {
		if ( current_user_can( 'manage_options' ) ) {
			printf(
				'<ul class="novacore-nav-bar__menu"><li><a href="%s">%s</a></li></ul>',
				esc_url( admin_url( 'nav-menus.php' ) ),
				esc_html__( 'Create a menu', 'novacore' )
			);
		}
	}
}

if ( ! function_exists( 'novacore_mega_menu_items' ) ) {
	function novacore_mega_menu_items( array $items ): array {
		$items_by_parent = [];
		foreach ( $items as $item ) {
			$items_by_parent[ $item->menu_item_parent ][] = $item;
		}

		$has_plus = [];
		foreach ( $items as $item ) {
			if ( str_starts_with( $item->title, '+' ) && ! str_starts_with( $item->title, '++' ) ) {
				$has_plus[ $item->menu_item_parent ] = true;
			}
		}

		foreach ( $items as $item ) {
			if ( ! empty( $has_plus[ $item->ID ] ) ) {
				$item->classes[] = 'mega-menu-trigger';
			}
		}

		$plus_parent_map = [];
		foreach ( $items_by_parent as $parent_id => $siblings ) {
			if ( empty( $has_plus[ $parent_id ] ) ) {
				continue;
			}
			$last_plus_id = null;
			foreach ( $siblings as $sib ) {
				if ( str_starts_with( $sib->title, '++' ) ) {
					$sib->title = ltrim( substr( $sib->title, 2 ) );
					$sib->menu_item_parent = (string) ( $last_plus_id ?? $parent_id );
					if ( $last_plus_id ) {
						$sib->classes[] = 'mega-menu-link';
					}
				} elseif ( str_starts_with( $sib->title, '+' ) ) {
					$sib->title = ltrim( substr( $sib->title, 1 ) );
					$sib->classes[] = 'mega-menu-col';
					$last_plus_id = $sib->ID;
				}
			}
		}

		return $items;
	}
}
add_filter( 'wp_nav_menu_objects', 'novacore_mega_menu_items', 10, 2 );

if ( ! function_exists( 'novacore_nav_menu_icons' ) ) {
	function novacore_nav_menu_icons( array $items ): array {
		foreach ( $items as $item ) {
			if ( preg_match( '/\{fa-([^}]+)\}/', $item->title, $matches ) ) {
				$icon_name = trim( $matches[1] );
				if ( false !== strpos( $icon_name, ' ' ) ) {
					$icon_class = 'fa-' . $icon_name;
				} else {
					$icon_class = 'fa-solid fa-' . $icon_name;
				}
				$icon        = '<i class="' . esc_attr( $icon_class ) . ' nav-menu-icon"></i>';
				$item->title = preg_replace( '/\{fa-[^}]+}\s*/', '', $item->title );
				$item->title = $icon . ' ' . $item->title;
			}
		}
		return $items;
	}
}
add_filter( 'wp_nav_menu_objects', 'novacore_nav_menu_icons', 5, 1 );

if ( ! function_exists( 'novacore_nav_home_link' ) ) {
	function novacore_nav_home_link( array $items ): array {
		$home_url  = home_url( '/' );
		$home_text = get_theme_mod( 'novacore_home_link_text', esc_html__( 'Home', 'novacore' ) );

		foreach ( $items as $item ) {
			if ( trailingslashit( $item->url ) === $home_url ) {
				$item->title = preg_replace( '/<i[^>]*><\/i>\s*/', '', $item->title );
				$item->title = preg_replace( '/\{fa-[^}]+}\s*/', '', $item->title );
				$item->title = '<i class="fa-solid fa-house nav-menu-icon"></i> ' . esc_html( $home_text );
			}
		}
		return $items;
	}
}
add_filter( 'wp_nav_menu_objects', 'novacore_nav_home_link', 6, 1 );

if ( ! function_exists( 'novacore_ad_area' ) ) {
	function novacore_ad_area( string $area ): void {
		if ( is_active_sidebar( "ad-{$area}" ) ) {
			$spacing = get_theme_mod( 'novacore_ad_spacing', 20 );
			echo '<div class="novacore-ad-area novacore-ad-area--' . esc_attr( $area ) . '" style="--nc-ad-spacing: ' . esc_attr( $spacing ) . 'px;">';
			echo '<div class="novacore-container">';
			dynamic_sidebar( "ad-{$area}" );
			echo '</div>';
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'novacore_render_social_icons' ) ) {
	function novacore_render_social_icons(): void {
		$networks = apply_filters( 'novacore_social_networks', [
			'facebook'      => [ 'label' => 'Facebook',       'icon' => 'fa-brands fa-facebook-f' ],
			'twitter'       => [ 'label' => 'Twitter / X',    'icon' => 'fa-brands fa-x-twitter' ],
			'instagram'     => [ 'label' => 'Instagram',      'icon' => 'fa-brands fa-instagram' ],
			'linkedin'      => [ 'label' => 'LinkedIn',       'icon' => 'fa-brands fa-linkedin-in' ],
			'youtube'       => [ 'label' => 'YouTube',        'icon' => 'fa-brands fa-youtube' ],
			'tiktok'        => [ 'label' => 'TikTok',         'icon' => 'fa-brands fa-tiktok' ],
			'snapchat'      => [ 'label' => 'Snapchat',       'icon' => 'fa-brands fa-snapchat' ],
			'pinterest'     => [ 'label' => 'Pinterest',      'icon' => 'fa-brands fa-pinterest' ],
			'whatsapp'      => [ 'label' => 'WhatsApp',       'icon' => 'fa-brands fa-whatsapp' ],
			'telegram'      => [ 'label' => 'Telegram',       'icon' => 'fa-brands fa-telegram' ],
			'discord'       => [ 'label' => 'Discord',        'icon' => 'fa-brands fa-discord' ],
			'twitch'        => [ 'label' => 'Twitch',         'icon' => 'fa-brands fa-twitch' ],
			'reddit'        => [ 'label' => 'Reddit',         'icon' => 'fa-brands fa-reddit' ],
			'medium'        => [ 'label' => 'Medium',         'icon' => 'fa-brands fa-medium' ],
			'tumblr'        => [ 'label' => 'Tumblr',         'icon' => 'fa-brands fa-tumblr' ],
			'vimeo'         => [ 'label' => 'Vimeo',          'icon' => 'fa-brands fa-vimeo-v' ],
			'dribbble'      => [ 'label' => 'Dribbble',       'icon' => 'fa-brands fa-dribbble' ],
			'behance'       => [ 'label' => 'Behance',        'icon' => 'fa-brands fa-behance' ],
			'soundcloud'    => [ 'label' => 'SoundCloud',     'icon' => 'fa-brands fa-soundcloud' ],
			'spotify'       => [ 'label' => 'Spotify',        'icon' => 'fa-brands fa-spotify' ],
			'threads'       => [ 'label' => 'Threads',        'icon' => 'fa-brands fa-threads' ],
			'bluesky'       => [ 'label' => 'Bluesky',        'icon' => 'fa-brands fa-bluesky' ],
			'mastodon'      => [ 'label' => 'Mastodon',       'icon' => 'fa-brands fa-mastodon' ],
			'flickr'        => [ 'label' => 'Flickr',         'icon' => 'fa-brands fa-flickr' ],
			'deviantart'    => [ 'label' => 'DeviantArt',     'icon' => 'fa-brands fa-deviantart' ],
			'github'        => [ 'label' => 'GitHub',         'icon' => 'fa-brands fa-github' ],
			'codepen'       => [ 'label' => 'CodePen',        'icon' => 'fa-brands fa-codepen' ],
			'stackoverflow' => [ 'label' => 'Stack Overflow', 'icon' => 'fa-brands fa-stack-overflow' ],
			'patreon'       => [ 'label' => 'Patreon',        'icon' => 'fa-brands fa-patreon' ],
			'etsy'          => [ 'label' => 'Etsy',           'icon' => 'fa-brands fa-etsy' ],
			'bandcamp'      => [ 'label' => 'Bandcamp',       'icon' => 'fa-brands fa-bandcamp' ],
			'slack'         => [ 'label' => 'Slack',          'icon' => 'fa-brands fa-slack' ],
			'xbox'          => [ 'label' => 'Xbox',           'icon' => 'fa-brands fa-xbox' ],
			'playstation'   => [ 'label' => 'PlayStation',    'icon' => 'fa-brands fa-playstation' ],
			'steam'         => [ 'label' => 'Steam',          'icon' => 'fa-brands fa-steam' ],
			'vk'            => [ 'label' => 'VK',             'icon' => 'fa-brands fa-vk' ],
			'weibo'         => [ 'label' => 'Weibo',          'icon' => 'fa-brands fa-weibo' ],
			'line'          => [ 'label' => 'LINE',           'icon' => 'fa-brands fa-line' ],
			'goodreads'     => [ 'label' => 'Goodreads',      'icon' => 'fa-brands fa-goodreads-g' ],
			'quora'         => [ 'label' => 'Quora',          'icon' => 'fa-brands fa-quora' ],
			'imdb'          => [ 'label' => 'IMDb',           'icon' => 'fa-brands fa-imdb' ],
		] );

		$output = '';

		foreach ( $networks as $network => $data ) {
			$url = get_theme_mod( "novacore_social_{$network}", '' );
			if ( ! $url ) {
				continue;
			}
			$output .= sprintf(
				'<a href="%s" class="novacore-social-icons__link" target="_blank" rel="noopener noreferrer" aria-label="%s"><i class="%s"></i></a>',
				esc_url( $url ),
				esc_attr( $data['label'] ),
				esc_attr( $data['icon'] )
			);
		}

		$custom = get_theme_mod( 'novacore_social_custom', '' );
		if ( ! empty( $custom ) ) {
			$lines = explode( "\n", $custom );
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( empty( $line ) ) {
					continue;
				}
				$parts = explode( '|', $line );
				if ( count( $parts ) < 3 ) {
					continue;
				}
				$icon_class = trim( $parts[0] );
				$label      = trim( $parts[1] );
				$url        = trim( $parts[2] );
				if ( empty( $icon_class ) || empty( $url ) ) {
					continue;
				}
				$output .= sprintf(
					'<a href="%s" class="novacore-social-icons__link" target="_blank" rel="noopener noreferrer" aria-label="%s"><i class="%s"></i></a>',
					esc_url( $url ),
					esc_attr( $label ),
					esc_attr( $icon_class )
				);
			}
		}

		if ( $output ) {
			printf(
				'<div class="novacore-social-icons">%s</div>',
				$output
			);
		}
	}
}

if ( ! function_exists( 'novacore_footer_menu' ) ) {
	function novacore_footer_menu(): void {
		$custom = get_theme_mod( 'novacore_footer_menu_links', '' );

		if ( ! empty( $custom ) ) {
			$lines = explode( "\n", $custom );
			echo '<ul class="novacore-footer__menu">';
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( empty( $line ) ) {
					continue;
				}
				$parts = explode( '|', $line );
				if ( count( $parts ) < 2 ) {
					continue;
				}
				$label = trim( $parts[0] );
				$url   = trim( $parts[1] );
				if ( empty( $label ) || empty( $url ) ) {
					continue;
				}
				printf(
					'<li><a href="%s">%s</a></li>',
					esc_url( $url ),
					esc_html( $label )
				);
			}
			echo '</ul>';
		} elseif ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu( [
				'theme_location' => 'footer',
				'menu_class'     => 'novacore-footer__menu',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			] );
		}
	}
}

if ( ! function_exists( 'novacore_footer_default_column' ) ) {
	function novacore_footer_default_column( int $column ): void {
		switch ( $column ) {
			case 1:
				?>
				<section class="widget">
					<?php if ( has_custom_logo() ) : ?>
						<div class="novacore-footer__brand"><?php the_custom_logo(); ?></div>
					<?php else : ?>
						<h3 class="widget-title"><?php bloginfo( 'name' ); ?></h3>
					<?php endif; ?>
					<p style="font-size:var(--nc-font-size-sm);color:var(--nc-contrast);line-height:1.7;">
						<?php echo esc_html( get_bloginfo( 'description' ) ?: __( 'A modern WordPress theme built for performance and flexibility.', 'novacore' ) ); ?>
					</p>
					<?php
					if ( function_exists( 'novacore_render_social_icons' ) ) {
						novacore_render_social_icons();
					}
					?>
				</section>
				<?php
				break;

			case 2:
				?>
				<section class="widget widget_nav_menu">
					<h3 class="widget-title"><?php esc_html_e( 'Quick Links', 'novacore' ); ?></h3>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'novacore' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'novacore' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'novacore' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'novacore' ); ?></a></li>
						<?php if ( function_exists( 'WC' ) ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Shop', 'novacore' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</section>
				<?php
				break;

			case 3:
				?>
				<section class="widget">
					<h3 class="widget-title"><?php esc_html_e( 'Get In Touch', 'novacore' ); ?></h3>
					<ul style="list-style:none;margin:0;padding:0;">
						<li style="padding:var(--nc-space-2) 0;color:var(--nc-contrast);font-size:var(--nc-font-size-sm);">
							<strong><?php esc_html_e( 'Email:', 'novacore' ); ?></strong><br>
							<a href="mailto:<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>"><?php echo esc_html( get_bloginfo( 'admin_email' ) ); ?></a>
						</li>
						<li style="padding:var(--nc-space-2) 0;color:var(--nc-contrast);font-size:var(--nc-font-size-sm);">
							<strong><?php esc_html_e( 'Address:', 'novacore' ); ?></strong><br>
							<?php esc_html_e( '123 Main Street, City, Country', 'novacore' ); ?>
						</li>
					</ul>
				</section>
				<?php
				break;
		}
	}
}

if ( ! function_exists( 'novacore_magazine_grid' ) ) {
	function novacore_magazine_grid( array $posts_data, string $section_title = '', string $exclude_key = '' ): array {
		if ( empty( $posts_data ) ) {
			return [];
		}

		$ids = [];
		$primary = $posts_data[0];
		$ids[] = $primary['id'];
		?>
		<div class="novacore-magazine-grid">
			<?php if ( $section_title ) : ?>
				<div class="novacore-magazine-grid__header">
					<h2 class="novacore-magazine-grid__title"><?php echo esc_html( $section_title ); ?></h2>
				</div>
			<?php endif; ?>
			<div class="novacore-magazine-grid__primary">
				<article class="novacore-mag-featured">
					<a href="<?php echo esc_url( $primary['permalink'] ); ?>" class="novacore-mag-featured__thumbnail">
						<?php if ( $primary['thumbnail'] ) : ?>
							<?php echo $primary['thumbnail']; ?>
						<?php endif; ?>
						<div class="novacore-mag-featured__overlay">
							<?php if ( ! empty( $primary['cats'] ) ) : ?>
							<span class="novacore-mag-featured__cat"><?php echo esc_html( $primary['cats'][0]->name ); ?></span>
							<?php endif; ?>
							<h2 class="novacore-mag-featured__title">
								<?php echo esc_html( $primary['title'] ); ?>
							</h2>
							<div class="novacore-mag-featured__meta">
								<span class="novacore-mag-featured__date"><?php echo esc_html( $primary['date'] ); ?></span>
								<span class="novacore-mag-featured__author"><?php echo esc_html( $primary['author'] ); ?></span>
							</div>
						</div>
					</a>
				</article>
			</div>
			<div class="novacore-magazine-grid__side">
				<?php for ( $i = 1; $i < count( $posts_data ); $i++ ) : $ids[] = $posts_data[ $i ]['id']; ?>
				<article class="novacore-mag-side">
					<a href="<?php echo esc_url( $posts_data[ $i ]['permalink'] ); ?>" class="novacore-mag-side__thumbnail">
						<?php if ( $posts_data[ $i ]['thumbnail'] ) : ?>
							<?php echo get_the_post_thumbnail( $posts_data[ $i ]['id'], 'novacore-sm' ); ?>
						<?php endif; ?>
						<div class="novacore-mag-side__overlay">
							<?php if ( ! empty( $posts_data[ $i ]['cats'] ) ) : ?>
							<span class="novacore-mag-side__cat"><?php echo esc_html( $posts_data[ $i ]['cats'][0]->name ); ?></span>
							<?php endif; ?>
							<h3 class="novacore-mag-side__title">
								<?php echo esc_html( $posts_data[ $i ]['title'] ); ?>
							</h3>
							<span class="novacore-mag-side__date"><?php echo esc_html( $posts_data[ $i ]['date'] ); ?></span>
						</div>
					</a>
				</article>
				<?php endfor; ?>
				</div>
			</div>
		</div>
		<?php
		return $ids;
	}
}

if ( ! function_exists( 'novacore_share_buttons' ) ) {
	function novacore_share_buttons( string $context = 'card' ): void {
		$url   = rawurlencode( get_permalink() );
		$title = rawurlencode( get_the_title() );

		$networks = [
			'facebook' => [
				'icon' => 'fa-brands fa-facebook-f',
				'url'  => "https://www.facebook.com/sharer/sharer.php?u={$url}",
				'label' => __( 'Share on Facebook', 'novacore' ),
			],
			'twitter' => [
				'icon' => 'fa-brands fa-x-twitter',
				'url'  => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
				'label' => __( 'Share on X', 'novacore' ),
			],
			'linkedin' => [
				'icon' => 'fa-brands fa-linkedin-in',
				'url'  => "https://www.linkedin.com/shareArticle?mini=true&url={$url}&title={$title}",
				'label' => __( 'Share on LinkedIn', 'novacore' ),
			],
			'pinterest' => [
				'icon' => 'fa-brands fa-pinterest-p',
				'url'  => "https://pinterest.com/pin/create/button/?url={$url}&description={$title}",
				'label' => __( 'Pin on Pinterest', 'novacore' ),
			],
			'telegram' => [
				'icon' => 'fa-brands fa-telegram',
				'url'  => "https://t.me/share/url?url={$url}&text={$title}",
				'label' => __( 'Share on Telegram', 'novacore' ),
			],
			'reddit' => [
				'icon' => 'fa-brands fa-reddit-alien',
				'url'  => "https://reddit.com/submit?url={$url}&title={$title}",
				'label' => __( 'Share on Reddit', 'novacore' ),
			],
			'whatsapp' => [
				'icon' => 'fa-brands fa-whatsapp',
				'url'  => "https://api.whatsapp.com/send?text={$url}",
				'label' => __( 'Share on WhatsApp', 'novacore' ),
			],
			'email' => [
				'icon' => 'fa-solid fa-envelope',
				'url'  => "mailto:?subject={$title}&body={$url}",
				'label' => __( 'Share via Email', 'novacore' ),
			],
		];

		$class = 'card' === $context ? 'novacore-share--card' : 'novacore-share--single';
		?>
		<div class="novacore-share <?php echo esc_attr( $class ); ?>">
			<span class="novacore-share__label"><?php esc_html_e( 'Share', 'novacore' ); ?></span>
			<div class="novacore-share__buttons">
				<?php foreach ( $networks as $network => $data ) : ?>
					<a href="<?php echo esc_url( $data['url'] ); ?>"
						class="novacore-share__link novacore-share__link--<?php echo esc_attr( $network ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $data['label'] ); ?>">
						<i class="<?php echo esc_attr( $data['icon'] ); ?>"></i>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'novacore_pingback_url' ) ) {
	function novacore_pingback_url(): void {
		if ( is_singular() && pings_open() ) {
			printf(
				'<link rel="pingback" href="%s">',
				esc_url( get_bloginfo( 'pingback_url' ) )
			);
		}
	}
}
add_action( 'wp_head', 'novacore_pingback_url' );

if ( ! function_exists( 'novacore_feature_grid' ) ) {
	function novacore_feature_grid( string $location = '' ): void {
		if ( ! $location ) {
			return;
		}

		$show = get_theme_mod( "novacore_feature_grid_{$location}_show", true );
		if ( ! $show ) {
			return;
		}

		$cols  = (int) get_theme_mod( "novacore_feature_grid_{$location}_cols", 4 );
		$cols  = min( max( 1, $cols ), 4 );

		$raw   = get_theme_mod( "novacore_feature_grid_{$location}_items", '' );
		$items = [];

		if ( ! empty( trim( $raw ) ) ) {
			$lines = explode( "\n", $raw );
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( empty( $line ) ) {
					continue;
				}
				$parts = explode( '|', $line );
				if ( count( $parts ) < 3 ) {
					continue;
				}
				$items[] = [
					'icon'  => trim( $parts[0] ),
					'title' => trim( $parts[1] ),
					'desc'  => trim( $parts[2] ),
				];
			}
		}

		if ( empty( $items ) ) {
			$items = apply_filters( 'novacore_feature_grid_defaults', [
				[
					'icon'  => 'fa-solid fa-newspaper',
					'title' => __( 'Breaking News', 'novacore' ),
					'desc'  => __( 'Stay informed with real-time updates and in-depth coverage of events shaping our world.', 'novacore' ),
				],
				[
					'icon'  => 'fa-solid fa-chart-line',
					'title' => __( 'Market Analysis', 'novacore' ),
					'desc'  => __( 'Expert insights and data-driven analysis on global markets, trends, and economic shifts.', 'novacore' ),
				],
				[
					'icon'  => 'fa-solid fa-video',
					'title' => __( 'Video Reports', 'novacore' ),
					'desc'  => __( 'Watch original documentaries, interviews, and investigative reports produced by our team.', 'novacore' ),
				],
				[
					'icon'  => 'fa-solid fa-podcast',
					'title' => __( 'Podcasts', 'novacore' ),
					'desc'  => __( 'Listen to thought-provoking discussions and expert opinions on the topics that matter.', 'novacore' ),
				],
			], $location );
		}

		if ( empty( $items ) ) {
			return;
		}
		?>
		<div class="novacore-feature-grid novacore-feature-grid--cols-<?php echo (int) $cols; ?> novacore-feature-grid--<?php echo esc_attr( $location ); ?>">
			<div class="novacore-container">
				<div class="novacore-feature-grid__grid">
					<?php foreach ( $items as $item ) : ?>
						<div class="novacore-feature-grid__item">
							<div class="novacore-feature-grid__icon">
								<i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
							</div>
							<h3 class="novacore-feature-grid__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
							<p class="novacore-feature-grid__item-desc"><?php echo esc_html( $item['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
