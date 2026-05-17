<?php
/**
 * Template Name: Contact
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="novacore-container">
	<div class="novacore-layout novacore-layout--full">
		<main id="main" class="novacore-main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'novacore-contact' ); ?>>
			<div class="novacore-contact__info">
				<h1 class="novacore-contact__info-title">
					<?php the_title(); ?>
				</h1>
				<div class="novacore-contact__info-text">
					<?php the_content(); ?>
				</div>
				<div class="novacore-contact__details">
					<?php
					$phone   = get_post_meta( get_the_ID(), 'novacore_contact_phone', true );
					$email   = get_post_meta( get_the_ID(), 'novacore_contact_email', true );
					$address = get_post_meta( get_the_ID(), 'novacore_contact_address', true );
					?>
					<?php if ( $phone ) : ?>
					<div class="novacore-contact__detail">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<div>
							<div class="novacore-contact__detail-label"><?php esc_html_e( 'Phone', 'novacore' ); ?></div>
							<div class="novacore-contact__detail-value">
								<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<?php if ( $email ) : ?>
					<div class="novacore-contact__detail">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						<div>
							<div class="novacore-contact__detail-label"><?php esc_html_e( 'Email', 'novacore' ); ?></div>
							<div class="novacore-contact__detail-value">
								<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<?php if ( $address ) : ?>
					<div class="novacore-contact__detail">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						<div>
							<div class="novacore-contact__detail-label"><?php esc_html_e( 'Address', 'novacore' ); ?></div>
							<div class="novacore-contact__detail-value"><?php echo esc_html( $address ); ?></div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="novacore-contact__form">
				<?php
				$form_shortcode = get_post_meta( get_the_ID(), 'novacore_contact_form_shortcode', true );
				if ( $form_shortcode ) {
					echo do_shortcode( wp_kses_post( $form_shortcode ) );
				}
				?>
			</div>
		</article>

		<?php
		endwhile;
		?>

		</main>
	</div>
</div>

<?php
get_footer();
