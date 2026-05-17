<?php
/**
 * Video Post Format Content
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-card novacore-card--video' ); ?>>
	<div class="novacore-card__media">
		<?php
		$video_url = get_post_meta( get_the_ID(), '_novacore_video_url', true );
		$video_mp4 = get_post_meta( get_the_ID(), '_novacore_video_mp4', true );
		if ( $video_url ) {
			echo wp_oembed_get( esc_url( $video_url ), [ 'width' => 800 ] );
		} elseif ( $video_mp4 ) {
			echo do_shortcode( '[video src="' . esc_url( $video_mp4 ) . '" poster="' . esc_url( get_the_post_thumbnail_url() ) . '"]' );
		} elseif ( has_post_thumbnail() ) {
			the_post_thumbnail( 'novacore-md', [ 'loading' => 'lazy' ] );
		}
		?>
		<div class="novacore-card__play-icon">
			<svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
				<path d="M8 5v14l11-7z"/>
			</svg>
		</div>
	</div>

	<div class="novacore-card__body">
		<?php the_title( '<h2 class="novacore-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		<div class="novacore-card__meta">
			<?php echo esc_html( get_the_date() ); ?>
		</div>
		<div class="novacore-card__excerpt">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
