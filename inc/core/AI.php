<?php
/**
 * NovaCore AI Features Module
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class AI {

	private string $api_endpoint = '';
	private string $api_key      = '';

	public function __construct() {
		$this->api_endpoint = get_theme_mod( 'novacore_ai_endpoint', 'https://api.openai.com/v1/chat/completions' );
		$this->api_key      = defined( 'NOVACORE_AI_KEY' ) ? NOVACORE_AI_KEY : '';

		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'wp_ajax_novacore_ai_suggestions', [ $this, 'get_suggestions' ] );
		add_action( 'wp_ajax_novacore_ai_seo_analysis', [ $this, 'seo_analysis' ] );
		add_action( 'wp_ajax_novacore_ai_alt_text', [ $this, 'generate_alt_text' ] );
		add_action( 'wp_ajax_novacore_ai_title_suggestions', [ $this, 'title_suggestions' ] );

		add_filter( 'novacore_ai_meta_box_context', [ $this, 'default_meta_box_context' ] );
	}

	public function add_meta_boxes(): void {
		$post_types = apply_filters( 'novacore_ai_post_types', [ 'post', 'page' ] );

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'novacore-ai',
				esc_html__( 'NovaCore AI', 'novacore' ),
				[ $this, 'render_meta_box' ],
				$post_type,
				apply_filters( 'novacore_ai_meta_box_context', 'side' ),
				'default'
			);
		}
	}

	public function render_meta_box( \WP_Post $post ): void {
		wp_nonce_field( 'novacore_ai_meta', 'novacore_ai_nonce' );
		?>
		<p style="margin-top:0;">
			<button type="button" class="button novacore-ai-btn"
				data-action="novacore_ai_suggestions"
				data-post-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php esc_html_e( 'Content Suggestions', 'novacore' ); ?>
			</button>
		</p>
		<p>
			<button type="button" class="button novacore-ai-btn"
				data-action="novacore_ai_title_suggestions"
				data-post-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php esc_html_e( 'Title Ideas', 'novacore' ); ?>
			</button>
		</p>
		<p>
			<button type="button" class="button novacore-ai-btn"
				data-action="novacore_ai_seo_analysis"
				data-post-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php esc_html_e( 'SEO Analysis', 'novacore' ); ?>
			</button>
		</p>
		<div class="novacore-ai-results" style="margin-top:12px;padding:8px;background:#f8fafc;border-radius:4px;font-size:12px;display:none;"></div>
		<?php
	}

	public function get_suggestions(): void {
		$this->verify_request();

		$post_id  = absint( $_POST['post_id'] );
		$post     = get_post( $post_id );
		$prompt   = sprintf(
			'Provide content suggestions to improve this article about "%s". Keep it brief, 3 bullet points.',
			$post->post_title
		);

		$result = $this->call_api( $prompt );
		wp_send_json_success( [ 'content' => $result ] );
	}

	public function seo_analysis(): void {
		$this->verify_request();

		$post_id  = absint( $_POST['post_id'] );
		$post     = get_post( $post_id );
		$prompt   = sprintf(
			'Provide a brief SEO analysis for an article titled "%s" (word count: ~%d). Give 3 actionable tips.',
			$post->post_title,
			str_word_count( wp_strip_all_tags( $post->post_content ) )
		);

		$result = $this->call_api( $prompt );
		wp_send_json_success( [ 'content' => $result ] );
	}

	public function generate_alt_text(): void {
		$this->verify_request();

		$image_url = esc_url_raw( $_POST['image_url'] );
		$prompt    = sprintf(
			'Generate a concise, SEO-friendly alt text for this image: %s. Max 125 characters.',
			$image_url
		);

		$result = $this->call_api( $prompt );
		wp_send_json_success( [ 'content' => $result ] );
	}

	public function title_suggestions(): void {
		$this->verify_request();

		$post_id  = absint( $_POST['post_id'] );
		$post     = get_post( $post_id );
		$content  = wp_trim_words( wp_strip_all_tags( $post->post_content ), 50 );
		$prompt   = sprintf(
			'Suggest 5 SEO-optimized blog post titles based on this content. Return as a simple numbered list. Content: %s',
			$content
		);

		$result = $this->call_api( $prompt );
		wp_send_json_success( [ 'content' => $result ] );
	}

	private function verify_request(): void {
		check_ajax_referer( 'novacore_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( 'Insufficient permissions.' );
		}
	}

	private function call_api( string $prompt ): string {
		if ( empty( $this->api_key ) ) {
			return __( 'AI features require an API key. Define NOVACORE_AI_KEY in wp-config.php.', 'novacore' );
		}

		$body = [
			'model'    => apply_filters( 'novacore_ai_model', 'gpt-4o-mini' ),
			'messages' => [
				[
					'role'    => 'system',
					'content' => 'You are a helpful WordPress content assistant. Keep responses concise and actionable.',
				],
				[
					'role'    => 'user',
					'content' => $prompt,
				],
			],
			'max_tokens'  => 300,
			'temperature' => 0.7,
		];

		$response = wp_remote_post( $this->api_endpoint, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->api_key,
				'Content-Type'  => 'application/json',
			],
			'body'    => wp_json_encode( $body ),
			'timeout' => 30,
		] );

		if ( is_wp_error( $response ) ) {
			return sprintf(
				/* translators: %s: error message */
				__( 'API error: %s', 'novacore' ),
				$response->get_error_message()
			);
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $data['choices'][0]['message']['content'] ) ) {
			return $data['choices'][0]['message']['content'];
		}

		return __( 'Unable to generate response at this time.', 'novacore' );
	}

	public function default_meta_box_context(): string {
		return 'side';
	}
}
