<?php
/**
 * Comments Template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="novacore-comments">

	<?php if ( have_comments() ) : ?>
		<h2 class="novacore-comments__title">
			<?php
			$comment_count = get_comments_number();
			if ( '1' === $comment_count ) {
				printf(
					esc_html__( 'One thought on &ldquo;%s&rdquo;', 'novacore' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s thought on &ldquo;%2$s&rdquo;',
						'%1$s thoughts on &ldquo;%2$s&rdquo;',
						$comment_count,
						'comments title',
						'novacore'
					),
					number_format_i18n( $comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="novacore-comments__list">
			<?php
			wp_list_comments( [
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
				'callback'    => 'novacore_comment_callback',
			] );
			?>
		</ol>

		<?php the_comments_navigation(); ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'novacore' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form( [
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h3>',
		'class_submit'       => 'novacore-btn novacore-btn--primary submit',
		'label_submit'       => esc_html__( 'Post Comment', 'novacore' ),
	] );
	?>

</div>
<?php

if ( ! function_exists( 'novacore_comment_callback' ) ) {
	function novacore_comment_callback( $comment, $args, $depth ): void {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'novacore-comment' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="novacore-comment__body">
				<footer class="novacore-comment__meta">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'], '', '', [ 'class' => 'novacore-comment__avatar' ] );
					}
					?>
					<div class="novacore-comment__author">
						<?php comment_author_link(); ?>
					</div>
					<div class="novacore-comment__time">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							printf(
								/* translators: 1: comment date, 2: comment time */
								esc_html__( '%1$s at %2$s', 'novacore' ),
								get_comment_date(),
								get_comment_time()
							);
							?>
						</time>
					</div>
				</footer>

				<div class="novacore-comment__content">
					<?php if ( '0' === $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation">
							<?php esc_html_e( 'Your comment is awaiting moderation.', 'novacore' ); ?>
						</p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>

				<div class="novacore-comment__reply">
					<?php
					comment_reply_link( array_merge( $args, [
						'reply_text' => esc_html__( 'Reply', 'novacore' ),
						'depth'      => $depth,
						'max_depth'  => $args['max_depth'],
						'before'     => '',
						'after'      => '',
					] ) );
					?>
				</div>
			</article>
		<?php
	}
}
