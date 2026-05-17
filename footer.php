<?php
/**
 * Theme Footer
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
	</div><!-- #primary -->

	<?php novacore_ad_area( 'before-footer' ); ?>

	<?php do_action( 'novacore_before_footer' ); ?>

	<footer id="colophon" class="novacore-footer" role="contentinfo">
		<?php do_action( 'novacore_footer_top' ); ?>

		<?php $ad_6 = get_theme_mod( 'novacore_ad_6_code' ); ?>
		<?php if ( $ad_6 ) : ?>
		<div class="novacore-ad-area novacore-ad-area--footer-top">
			<div class="novacore-container">
				<?php echo wp_kses_post( $ad_6 ); ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- Feature Grid - Before Subscribe -->
		<?php novacore_feature_grid( 'footer' ); ?>

		<div class="novacore-footer__top">
			<div class="novacore-container">
				<div class="novacore-footer__top-inner">
					<div class="novacore-footer__top-left">
						<?php
						if ( function_exists( 'novacore_render_social_icons' ) ) {
							novacore_render_social_icons();
						}
						?>
					</div>
					<div class="novacore-footer__top-right">
						<?php
						if ( function_exists( 'novacore_footer_menu' ) ) {
							novacore_footer_menu();
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<?php
		$footer_cols = get_theme_mod( 'novacore_footer_columns', 3 );
		$has_widgets = false;
		for ( $i = 1; $i <= $footer_cols; $i++ ) {
			if ( is_active_sidebar( "footer-{$i}" ) ) {
				$has_widgets = true;
				break;
			}
		}
		?>
		<div class="novacore-footer__widgets">
			<div class="novacore-container">
				<div class="novacore-footer__grid novacore-grid-<?php echo esc_attr( $footer_cols ); ?>">
					<?php for ( $i = 1; $i <= $footer_cols; $i++ ) : ?>
						<div class="novacore-footer__column">
						<?php if ( is_active_sidebar( "footer-{$i}" ) ) : ?>
							<?php dynamic_sidebar( "footer-{$i}" ); ?>
						<?php elseif ( ! $has_widgets ) : ?>
							<?php novacore_footer_default_column( $i ); ?>
						<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>

		<div class="novacore-footer__bottom">
			<div class="novacore-container">
				<div class="novacore-footer__inner">
					<div class="novacore-footer__bottom-left">
						<?php get_template_part( 'template-parts/footer/copyright' ); ?>
					</div>
					<div class="novacore-footer__bottom-right">
						<a href="https://github.com/MohamedSubarashi" class="novacore-dev-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Developer Profile', 'novacore' ); ?>">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
						</a>
					</div>
				</div>
			</div>
		</div>

		<?php do_action( 'novacore_footer_bottom' ); ?>
	</footer>

	<?php do_action( 'novacore_after_footer' ); ?>

	<button class="novacore-scroll-top" aria-label="<?php esc_attr_e( 'Scroll to top', 'novacore' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="12" y1="19" x2="12" y2="5"></line>
			<polyline points="5 12 12 5 19 12"></polyline>
		</svg>
	</button>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
