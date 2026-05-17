<?php
/**
 * Sidebar
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$has_trending  = is_active_sidebar( 'sidebar-trending' );
$has_main      = is_active_sidebar( 'sidebar-1' );
$has_cta       = is_active_sidebar( 'sidebar-cta' );
$has_bottom    = is_active_sidebar( 'sidebar-bottom' );

?>
<aside id="secondary" class="novacore-sidebar" role="complementary"
	aria-label="<?php esc_attr_e( 'Sidebar', 'novacore' ); ?>">

	<?php
	$has_social = get_theme_mod( 'novacore_social_facebook', '' )
		|| get_theme_mod( 'novacore_social_twitter', '' )
		|| get_theme_mod( 'novacore_social_instagram', '' )
		|| get_theme_mod( 'novacore_social_custom', '' );
	if ( $has_social && function_exists( 'novacore_render_social_icons' ) ) :
		?>
	<div class="novacore-sidebar__section novacore-sidebar__social">
		<section class="widget">
			<h3 class="widget-title"><?php esc_html_e( 'Follow Us', 'novacore' ); ?></h3>
			<?php novacore_render_social_icons(); ?>
		</section>
	</div>
	<?php endif; ?>

	<?php if ( ! $has_trending && ! $has_main && ! $has_cta && ! $has_bottom ) : ?>
	<div class="novacore-sidebar__section novacore-sidebar__main">
		<section class="widget widget_recent_entries">
			<h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'novacore' ); ?></h3>
			<ul>
				<?php
				$recent = wp_get_recent_posts( [ 'numberposts' => 5, 'post_status' => 'publish' ] );
				foreach ( $recent as $r ) :
					?>
				<li><a href="<?php echo esc_url( get_permalink( $r['ID'] ) ); ?>"><?php echo esc_html( $r['post_title'] ); ?></a></li>
				<?php endforeach; wp_reset_postdata(); ?>
			</ul>
		</section>
		<section class="widget widget_categories">
			<h3 class="widget-title"><?php esc_html_e( 'Categories', 'novacore' ); ?></h3>
			<ul>
				<?php wp_list_categories( [ 'title_li' => '', 'depth' => 1 ] ); ?>
			</ul>
		</section>
	<section class="widget widget_tag_cloud">
		<h3 class="widget-title"><?php esc_html_e( 'Tags', 'novacore' ); ?></h3>
		<div class="tagcloud">
			<?php wp_tag_cloud( [ 'smallest' => 12, 'largest' => 12, 'unit' => 'px' ] ); ?>
		</div>
	</section>
	</div>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-sticky-top' ) ) : ?>
	<div class="novacore-sidebar__sticky">
		<?php dynamic_sidebar( 'sidebar-sticky-top' ); ?>
	</div>
	<?php endif; ?>

	<?php if ( $has_trending ) : ?>
	<div class="novacore-sidebar__section novacore-sidebar__trending">
		<?php dynamic_sidebar( 'sidebar-trending' ); ?>
	</div>
	<?php endif; ?>

	<?php if ( $has_cta ) : ?>
	<div class="novacore-sidebar__section novacore-sidebar__cta">
		<?php dynamic_sidebar( 'sidebar-cta' ); ?>
	</div>
	<?php endif; ?>

	<?php if ( $has_main ) : ?>
	<div class="novacore-sidebar__section novacore-sidebar__main">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
	<?php endif; ?>

	<?php if ( $has_bottom ) : ?>
	<div class="novacore-sidebar__section novacore-sidebar__bottom">
		<?php dynamic_sidebar( 'sidebar-bottom' ); ?>
	</div>
	<?php endif; ?>
</aside>
