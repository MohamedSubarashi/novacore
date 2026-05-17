<?php
/**
 * Theme Header
 *
 * @package NovaCore
 * @since 1.0.0
 */

use NovaCore\Theme;

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="novacore-site">

	<?php do_action( 'novacore_before_header' ); ?>

	<!-- Header / Logo Area -->
	<header id="masthead" class="novacore-header" role="banner">
		<?php do_action( 'novacore_header_top' ); ?>

		<div class="novacore-header__main">
			<div class="novacore-container">
				<div class="novacore-header__inner">

					<div class="novacore-header__left">
						<span class="novacore-header__date"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></span>
						<?php do_action( 'novacore_header_left_actions' ); ?>
					</div>

					<div class="novacore-header__right">
						<?php
						if ( function_exists( 'novacore_render_social_icons' ) ) {
							novacore_render_social_icons();
						}
						do_action( 'novacore_header_right_actions' );
						?>
					</div>

				</div>
			</div>
		</div>

		<div class="novacore-header__brand-section">
			<div class="novacore-container">
				<div class="novacore-header__brand-inner">
					<div class="novacore-header__brand-col">
						<?php get_template_part( 'template-parts/header/site-branding' ); ?>
					</div>
					<?php $ad_1 = get_theme_mod( 'novacore_ad_1_code' ); ?>
					<?php if ( $ad_1 ) : ?>
					<div class="novacore-header__ad-col">
						<div class="novacore-ad-novacore-ad-1"><?php echo wp_kses_post( $ad_1 ); ?></div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php do_action( 'novacore_header_bottom' ); ?>
	</header>

	<!-- Primary Nav Bar -->
	<div class="novacore-nav-bar">
		<div class="novacore-container">
			<div class="novacore-nav-bar__inner">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_class'     => 'novacore-nav-bar__menu',
						'container'      => false,
						'depth'          => 4,
						'fallback_cb'    => 'novacore_primary_menu_fallback',
					] );
				}
				?>
			</div>
		</div>
	</div>

	<!-- Top Bar Panel (hidden, toggled by the top-bar toggle button) -->
	<div class="novacore-top-bar-panel" aria-hidden="true">
		<div class="novacore-container">
			<div class="novacore-top-bar-panel__inner">
				<div class="novacore-top-bar-panel__end">
					<?php
					if ( has_nav_menu( 'top-bar' ) ) {
						wp_nav_menu( [
							'theme_location' => 'top-bar',
							'menu_class'     => 'novacore-top-bar-panel__menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						] );
					}
					?>
				</div>
			</div>
		</div>
	</div>



	<?php do_action( 'novacore_after_header' ); ?>

	<?php if ( is_active_sidebar( 'ad-header' ) ) : ?>
	<div class="novacore-ad-area novacore-ad-area--header">
		<div class="novacore-container">
			<?php dynamic_sidebar( 'ad-header' ); ?>
		</div>
	</div>
	<?php endif; ?>

	<div id="primary" class="novacore-content">
