<?php
/**
 * Author Page Template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container">
	<div class="novacore-layout">
		<main id="main" class="novacore-main">

		<?php if ( have_posts() ) : ?>

			<?php
			$author_id   = get_the_author_meta( 'ID' );
			$author_name = get_the_author();
			$author_bio  = get_the_author_meta( 'description' );
			$author_avatar = get_avatar( $author_id, 120, '', '', [ 'class' => 'novacore-author-box__avatar-img' ] );
			?>

			<header class="novacore-author-box">
				<div class="novacore-author-box__avatar">
					<?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="novacore-author-box__info">
					<h1 class="novacore-author-box__name">
						<?php echo esc_html( $author_name ); ?>
					</h1>
					<?php if ( $author_bio ) : ?>
						<p class="novacore-author-box__bio"><?php echo esc_html( $author_bio ); ?></p>
					<?php endif; ?>
					<div class="novacore-author-box__social">
						<?php
						$user_url = get_the_author_meta( 'user_url' );
						if ( $user_url ) :
							?>
							<a href="<?php echo esc_url( $user_url ); ?>" class="novacore-author-box__social-link" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Website', 'novacore' ); ?>">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</header>

			<div class="novacore-posts novacore-posts--author" id="novacore-posts-container">
				<?php
				while ( have_posts() ) :
					the_post();
					novacore_content_template();
				endwhile;
				?>
			</div>

			<?php
			novacore_pagination();

		else :
			get_template_part( 'template-parts/content/content', 'none' );
		endif;
		?>

		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
