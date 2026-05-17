<?php
/**
 * Single Post Content Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-single' ); ?>>
	<header class="novacore-single__header">
		<?php
		$categories = get_the_category();
		if ( ! empty( $categories ) ) :
			?>
		<div class="novacore-single__categories">
			<?php foreach ( $categories as $category ) : ?>
				<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
					class="novacore-single__cat">
					<?php echo esc_html( $category->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php the_title( '<h1 class="novacore-single__title">', '</h1>' ); ?>

		<div class="novacore-single__meta">
			<div class="novacore-single__author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', [ 'class' => 'novacore-single__avatar' ] ); ?>
				<span class="novacore-single__author-name">
					<?php the_author_posts_link(); ?>
				</span>
			</div>
			<span class="novacore-single__date">
				<?php echo esc_html( get_the_date() ); ?>
			</span>
			<span class="novacore-single__reading-time">
				<?php echo esc_html( novacore_reading_time() ); ?>
			</span>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="novacore-single__thumbnail">
		<?php the_post_thumbnail( 'novacore-xl', [ 'loading' => 'lazy' ] ); ?>
	</div>
	<?php endif; ?>

	<?php $ad_3 = get_theme_mod( 'novacore_ad_3_code' ); ?>
	<?php if ( $ad_3 ) : ?>
	<div class="novacore-ad-area novacore-ad-area--post-top"><?php echo wp_kses_post( $ad_3 ); ?></div>
	<?php endif; ?>

	<div class="novacore-single__content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'novacore' ),
					[ 'span' => [ 'class' => [] ] ]
				),
				get_the_title()
			)
		);

		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'novacore' ),
			'after'  => '</div>',
		] );
		?>
	</div>

	<?php $ad_5 = get_theme_mod( 'novacore_ad_5_code' ); ?>
	<?php if ( $ad_5 ) : ?>
	<div class="novacore-ad-area novacore-ad-area--post-bottom"><?php echo wp_kses_post( $ad_5 ); ?></div>
	<?php endif; ?>

	<footer class="novacore-single__footer">
		<?php
		$tags = get_the_tags();
		if ( $tags ) :
			?>
		<div class="novacore-single__tags">
			<span class="novacore-single__tags-label"><?php esc_html_e( 'Tags:', 'novacore' ); ?></span>
			<?php foreach ( $tags as $tag ) : ?>
				<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
					class="novacore-single__tag">
					<?php echo esc_html( $tag->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( function_exists( 'novacore_share_buttons' ) ) : ?>
			<?php novacore_share_buttons( 'single' ); ?>
		<?php endif; ?>

		<?php novacore_entry_footer(); ?>
	</footer>
</article>
