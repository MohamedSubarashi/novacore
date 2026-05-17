<?php
/**
 * Author Box Template Part
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$author_id   = get_the_author_meta( 'ID' );
$author_name = get_the_author();
$author_desc = get_the_author_meta( 'description' );
$author_url  = get_author_posts_url( $author_id );
?>

<div class="novacore-author-box">
	<div class="novacore-author-box__avatar">
		<a href="<?php echo esc_url( $author_url ); ?>">
			<?php echo get_avatar( $author_id, 96, '', $author_name, [ 'class' => 'novacore-author-box__img' ] ); ?>
		</a>
	</div>
	<div class="novacore-author-box__info">
		<h3 class="novacore-author-box__name">
			<a href="<?php echo esc_url( $author_url ); ?>">
				<?php echo esc_html( $author_name ); ?>
			</a>
		</h3>
		<p class="novacore-author-box__bio">
			<?php echo esc_html( $author_desc ); ?>
		</p>
		<div class="novacore-author-box__social">
			<?php
			$user_twitter = get_the_author_meta( 'twitter', $author_id );
			if ( $user_twitter ) :
				?>
			<a href="<?php echo esc_url( $user_twitter ); ?>" class="novacore-author-box__social-link"
				aria-label="<?php esc_attr_e( 'Twitter/X profile', 'novacore' ); ?>" target="_blank" rel="noopener">
				<i class="fa-brands fa-x-twitter"></i>
			</a>
			<?php endif; ?>
			<?php
			$user_linkedin = get_the_author_meta( 'linkedin', $author_id );
			if ( $user_linkedin ) :
				?>
			<a href="<?php echo esc_url( $user_linkedin ); ?>" class="novacore-author-box__social-link"
				aria-label="<?php esc_attr_e( 'LinkedIn profile', 'novacore' ); ?>" target="_blank" rel="noopener">
				<i class="fa-brands fa-linkedin-in"></i>
			</a>
			<?php endif; ?>
			<?php
			$user_website = get_the_author_meta( 'user_url', $author_id );
			if ( $user_website ) :
				?>
			<a href="<?php echo esc_url( $user_website ); ?>" class="novacore-author-box__social-link"
				aria-label="<?php esc_attr_e( 'Website', 'novacore' ); ?>" target="_blank" rel="noopener">
				<i class="fa-solid fa-globe"></i>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>
