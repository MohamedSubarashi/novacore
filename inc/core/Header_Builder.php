<?php
/**
 * NovaCore Header Builder
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Header_Builder {

	private static ?Header_Builder $instance = null;

	public static function instance(): Header_Builder {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'novacore_header_right_actions', [ $this, 'render_search_toggle' ] );
		add_action( 'novacore_header_left_actions', [ $this, 'render_top_bar_toggle' ] );
		add_action( 'novacore_header_left_actions', [ $this, 'render_dark_mode_toggle' ], 15 );
		add_action( 'novacore_header_right_actions', [ $this, 'render_cart_toggle' ] );
		add_action( 'novacore_header_right_actions', [ $this, 'render_cta_button' ] );
		add_action( 'wp_footer', [ $this, 'render_search_popup' ], 5 );
		add_filter( 'wp_nav_menu_items', [ $this, 'add_mega_menu_indicators' ], 10, 2 );

		do_action( 'novacore_header_builder_init' );
	}

	public function render_search_toggle(): void {
		if ( ! get_theme_mod( 'novacore_show_search', true ) ) {
			return;
		}
		?>
		<button class="novacore-header__action-btn novacore-search-toggle"
			aria-label="<?php esc_attr_e( 'Toggle search', 'novacore' ); ?>"
			type="button">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
			</svg>
		</button>
		<?php
	}

	public function render_dark_mode_toggle(): void {
		if ( 'disabled' === get_theme_mod( 'novacore_dark_mode', 'system' ) ) {
			return;
		}
		?>
		<button class="novacore-header__action-btn novacore-dark-toggle"
			aria-label="<?php esc_attr_e( 'Toggle dark mode', 'novacore' ); ?>"
			type="button">
			<svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="12" cy="12" r="5"></circle>
				<line x1="12" y1="1" x2="12" y2="3"></line>
				<line x1="12" y1="21" x2="12" y2="23"></line>
				<line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
				<line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
				<line x1="1" y1="12" x2="3" y2="12"></line>
				<line x1="21" y1="12" x2="23" y2="12"></line>
				<line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
				<line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
			</svg>
			<svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
			</svg>
		</button>
		<?php
	}

	public function render_cart_toggle(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		if ( ! get_theme_mod( 'novacore_show_cart', true ) ) {
			return;
		}
		$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
		?>
		<button class="novacore-header__action-btn novacore-cart-toggle"
			aria-label="<?php esc_attr_e( 'Toggle cart', 'novacore' ); ?>"
			type="button">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="9" cy="21" r="1"></circle>
				<circle cx="20" cy="21" r="1"></circle>
				<path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"></path>
			</svg>
			<?php if ( $count > 0 ) : ?>
				<span class="novacore-cart-count"><?php echo esc_html( $count ); ?></span>
			<?php endif; ?>
		</button>
		<?php
	}

	public function render_top_bar_toggle(): void {
		if ( ! has_nav_menu( 'top-bar' ) && ! has_filter( 'novacore_render_social_icons' ) ) {
			return;
		}
		?>
		<button class="novacore-header__action-btn novacore-top-bar-toggle"
			aria-label="<?php esc_attr_e( 'Toggle menu', 'novacore' ); ?>"
			type="button" aria-expanded="false">
			<i class="fa-solid fa-bars"></i>
		</button>
		<?php
	}

	public function render_cta_button(): void {
		$cta_text  = get_theme_mod( 'novacore_header_cta_text', '' );
		$cta_url   = get_theme_mod( 'novacore_header_cta_url', '' );
		$cta_style = get_theme_mod( 'novacore_header_cta_style', 'primary' );

		if ( ! $cta_text || ! $cta_url ) {
			return;
		}
		?>
		<a href="<?php echo esc_url( $cta_url ); ?>"
			class="novacore-btn novacore-btn--<?php echo esc_attr( $cta_style ); ?> novacore-btn--sm hide-sm">
			<?php echo esc_html( $cta_text ); ?>
		</a>
		<?php
	}

	public function render_search_popup(): void {
		?>
		<div class="novacore-search-popup" role="dialog" aria-modal="true"
			aria-label="<?php esc_attr_e( 'Search', 'novacore' ); ?>">
			<div class="novacore-search-popup__inner">
				<?php get_search_form(); ?>
				<div class="novacore-search-popup__results"></div>
			</div>
			<button class="novacore-search-popup__close" aria-label="<?php esc_attr_e( 'Close search', 'novacore' ); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>
		<?php
	}

	public function add_mega_menu_indicators( string $items, \stdClass $args ): string {
		if ( 'primary' !== $args->theme_location ) {
			return $items;
		}

		$items = str_replace(
			'menu-item-has-children',
			'menu-item-has-children dropdown',
			$items
		);

		return $items;
	}
}
