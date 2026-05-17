<?php
/**
 * NovaCore Footer Builder
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Footer_Builder {

	private static ?Footer_Builder $instance = null;

	public static function instance(): Footer_Builder {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'novacore_footer_top', [ $this, 'render_newsletter_section' ], 5 );

		do_action( 'novacore_footer_builder_init' );
	}

	public function render_newsletter_section(): void {
		if ( ! get_theme_mod( 'novacore_footer_show_newsletter', false ) ) {
			return;
		}
		?>
		<div class="novacore-newsletter">
			<div class="novacore-container">
				<h2 class="novacore-newsletter__title">
					<?php echo esc_html( get_theme_mod( 'novacore_newsletter_title', __( 'Stay Updated', 'novacore' ) ) ); ?>
				</h2>
				<p class="novacore-newsletter__text">
					<?php echo esc_html( get_theme_mod( 'novacore_newsletter_text', __( 'Subscribe to our newsletter for the latest updates.', 'novacore' ) ) ); ?>
				</p>
				<form class="novacore-newsletter__form novacore-subscribe-form" action="#" method="post">
					<div class="novacore-newsletter-form">
						<input type="email" name="email"
							placeholder="<?php esc_attr_e( 'Enter your email', 'novacore' ); ?>"
							required />
						<button type="submit" class="novacore-btn novacore-btn--primary">
							<?php esc_html_e( 'Subscribe', 'novacore' ); ?>
						</button>
						<div class="novacore-subscribe-msg"></div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

}
