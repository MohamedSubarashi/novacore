<?php
/**
 * NovaCore Social Counter Widget
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Widgets;

defined( 'ABSPATH' ) || exit;

class Social_Counter extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'novacore_social_counter',
			esc_html__( 'NovaCore Social Counter', 'novacore' ),
			[ 'description' => esc_html__( 'Social media profile links with counters.', 'novacore' ) ]
		);
	}

	public function widget( $args, $instance ): void {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'novacore' );

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html( $title ) . $args['after_title'];

		$networks = [
			'facebook'  => [ 'label' => 'Facebook', 'class' => 'social-facebook' ],
			'twitter'   => [ 'label' => 'Twitter / X', 'class' => 'social-twitter' ],
			'instagram' => [ 'label' => 'Instagram', 'class' => 'social-instagram' ],
			'linkedin'  => [ 'label' => 'LinkedIn', 'class' => 'social-linkedin' ],
			'youtube'   => [ 'label' => 'YouTube', 'class' => 'social-youtube' ],
			'github'    => [ 'label' => 'GitHub', 'class' => 'social-github' ],
		];

		echo '<div class="novacore-social-counter">';
		foreach ( $networks as $key => $data ) {
			$url   = $instance[ "{$key}_url" ] ?? '';
			$count = $instance[ "{$key}_count" ] ?? '';
			if ( ! $url ) {
				continue;
			}
			?>
			<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $data['class'] ); ?>"
				target="_blank" rel="noopener noreferrer">
				<?php $this->render_network_icon( $key ); ?>
				<span class="novacore-social-counter__label"><?php echo esc_html( $data['label'] ); ?></span>
				<?php if ( $count ) : ?>
					<span class="novacore-social-counter__count"><?php echo esc_html( $count ); ?></span>
				<?php endif; ?>
			</a>
			<?php
		}
		echo '</div>';

		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$title = $instance['title'] ?? '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'novacore' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		$networks = [ 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'github' ];
		foreach ( $networks as $network ) :
			$url   = $instance[ "{$network}_url" ] ?? '';
			$count = $instance[ "{$network}_count" ] ?? '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "{$network}_url" ) ); ?>">
					<?php echo esc_html( ucfirst( $network ) . ' URL:' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( "{$network}_url" ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( "{$network}_url" ) ); ?>" type="url"
					value="<?php echo esc_attr( $url ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "{$network}_count" ) ); ?>">
					<?php echo esc_html( ucfirst( $network ) . ' Count:' ); ?>
				</label>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( "{$network}_count" ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( "{$network}_count" ) ); ?>" type="text"
					value="<?php echo esc_attr( $count ); ?>">
			</p>
		<?php endforeach; ?>
		<?php
	}

	private function render_network_icon( string $network ): void {
		$icons = [
			'facebook'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3V2z"/></svg>',
			'twitter'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
			'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
			'linkedin'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
			'youtube'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
			'github'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>',
		];
		echo $icons[ $network ] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function update( $new_instance, $old_instance ): array {
		$instance          = [];
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$networks = [ 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'github' ];
		foreach ( $networks as $network ) {
			$instance[ "{$network}_url" ]   = esc_url_raw( $new_instance[ "{$network}_url" ] ?? '' );
			$instance[ "{$network}_count" ] = sanitize_text_field( $new_instance[ "{$network}_count" ] ?? '' );
		}
		return $instance;
	}
}
