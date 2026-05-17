<?php
/**
 * NovaCore Team Block Render
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Blocks;

defined( 'ABSPATH' ) || exit;

class Team {

	public function render( array $attributes ): string {
		$name = $attributes['name'] ?? '';
		$role = $attributes['role'] ?? '';
		$bio  = $attributes['bio'] ?? '';

		ob_start();
		?>
		<div class="novacore-team-card">
			<div class="novacore-team-card__avatar">
				<div class="novacore-team-card__avatar-placeholder">
					<?php echo esc_html( $name ? substr( $name, 0, 2 ) : '?' ); ?>
				</div>
			</div>
			<?php if ( $name ) : ?>
				<h3 class="novacore-team-card__name"><?php echo esc_html( $name ); ?></h3>
			<?php endif; ?>
			<?php if ( $role ) : ?>
				<div class="novacore-team-card__role"><?php echo esc_html( $role ); ?></div>
			<?php endif; ?>
			<?php if ( $bio ) : ?>
				<p class="novacore-team-card__bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
