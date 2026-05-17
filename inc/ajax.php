<?php
/**
 * NovaCore AJAX Handlers
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore;

defined( 'ABSPATH' ) || exit;

class Ajax {

	public function __construct() {
		add_action( 'wp_ajax_novacore_ajax_search', [ $this, 'ajax_search' ] );
		add_action( 'wp_ajax_nopriv_novacore_ajax_search', [ $this, 'ajax_search' ] );
		add_action( 'wp_ajax_novacore_quick_view', [ $this, 'quick_view' ] );
		add_action( 'wp_ajax_nopriv_novacore_quick_view', [ $this, 'quick_view' ] );
		add_action( 'wp_ajax_novacore_ajax_pagination', [ $this, 'ajax_pagination' ] );
		add_action( 'wp_ajax_nopriv_novacore_ajax_pagination', [ $this, 'ajax_pagination' ] );
		add_action( 'wp_ajax_novacore_install_plugin', [ $this, 'install_plugin' ] );

		add_action( 'wp_ajax_novacore_newsletter_subscribe', [ $this, 'newsletter_subscribe' ] );
		add_action( 'wp_ajax_nopriv_novacore_newsletter_subscribe', [ $this, 'newsletter_subscribe' ] );

		do_action( 'novacore_ajax_init' );
	}

	public function ajax_search(): void {
		check_ajax_referer( 'novacore_nonce', 'nonce' );

		$search_query = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		if ( strlen( $search_query ) < 2 ) {
			wp_send_json_error( 'Query too short' );
		}

		$args = [
			'post_type'           => [ 'post', 'page' ],
			'posts_per_page'      => 8,
			's'                   => $search_query,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		];

		$query   = new \WP_Query( $args );
		$results = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$thumbnail = '';
				if ( has_post_thumbnail() ) {
					$thumbnail = get_the_post_thumbnail(
						get_the_ID(),
						'thumbnail',
						[ 'loading' => 'lazy', 'width' => 48, 'height' => 48 ]
					);
				}

				$post_type = get_post_type_object( get_post_type() );

				$results[] = [
					'title'     => get_the_title(),
					'url'       => get_permalink(),
					'thumbnail' => $thumbnail,
					'type'      => $post_type ? $post_type->labels->singular_name : '',
				];
			}
			wp_reset_postdata();
		}

		wp_send_json_success( $results );
	}

	public function quick_view(): void {
		check_ajax_referer( 'novacore_nonce', 'nonce' );

		$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;

		if ( ! $product_id || ! function_exists( 'wc_setup_product_data' ) ) {
			wp_send_json_error( 'Invalid product' );
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json_error( 'Product not found' );
		}

		setup_postdata( $product->get_id() );

		ob_start();
		?>
		<div class="novacore-quick-view__image">
			<?php echo wp_kses_post( $product->get_image( 'woocommerce_single' ) ); ?>
		</div>
		<div class="novacore-quick-view__details">
			<h2 class="novacore-quick-view__title"><?php echo esc_html( $product->get_name() ); ?></h2>
			<div class="novacore-quick-view__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<div class="novacore-quick-view__description">
				<?php echo wp_kses_post( wp_trim_words( $product->get_short_description(), 30 ) ); ?>
			</div>
			<?php
			woocommerce_template_loop_add_to_cart( [
				'product' => $product,
			] );
			?>
		</div>
		<?php
		$html = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success( [ 'html' => $html ] );
	}

	public function ajax_pagination(): void {
		check_ajax_referer( 'novacore_nonce', 'nonce' );

		$page       = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$query_vars = isset( $_POST['query_vars'] ) ? json_decode( wp_unslash( $_POST['query_vars'] ), true ) : [];

		if ( empty( $query_vars ) || ! is_array( $query_vars ) ) {
			wp_send_json_error( 'Invalid query vars' );
		}

		$query_vars['paged']          = $page;
		$query_vars['no_found_rows']  = false;
		$query_vars['post_status']    = 'publish';

		$query = new \WP_Query( $query_vars );

		ob_start();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/content/content', get_post_type() );
			}
			wp_reset_postdata();
		}

		$posts_html = ob_get_clean();

		$pagination_args = [
			'mid_size'           => 2,
			'prev_text'          => sprintf( '<span aria-hidden="true">&larr;</span> %s', esc_html__( 'Previous', 'novacore' ) ),
			'next_text'          => sprintf( '%s <span aria-hidden="true">&rarr;</span>', esc_html__( 'Next', 'novacore' ) ),
			'screen_reader_text' => esc_html__( 'Posts navigation', 'novacore' ),
		];

		$pagination_html = paginate_links( array_merge( $pagination_args, [
			'current' => $page,
			'total'   => $query->max_num_pages,
		] ) );

		if ( $pagination_html ) {
			$pagination_html = '<nav class="novacore-pagination" role="navigation" aria-label="' . esc_attr__( 'Pagination', 'novacore' ) . '">' . $pagination_html . '</nav>';
		}

		wp_send_json_success( [
			'html'       => $posts_html,
			'pagination' => $pagination_html,
			'max_pages'  => $query->max_num_pages,
			'page'       => $page,
		] );
	}

	public function install_plugin(): void {
		check_ajax_referer( 'novacore_nonce', '_ajax_nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$plugin_slug = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';

		if ( empty( $plugin_slug ) ) {
			wp_send_json_error( 'No plugin specified' );
		}

		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$api = plugins_api( 'plugin_information', [
			'slug'   => $plugin_slug,
			'fields' => [ 'sections' => false ],
		] );

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( $api->get_error_message() );
		}

		$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
		$result   = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		if ( ! $result ) {
			wp_send_json_error( 'Plugin installation failed' );
		}

		$plugin_file = $upgrader->plugin_info();
		if ( $plugin_file ) {
			activate_plugin( $plugin_file );
		}

		wp_send_json_success( 'Plugin installed and activated' );
	}

	public function newsletter_subscribe(): void {
		check_ajax_referer( 'novacore_nonce', 'nonce' );

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( esc_html__( 'Please enter a valid email address.', 'novacore' ) );
		}

		$subscribers = get_option( 'novacore_subscribers', [] );
		if ( ! is_array( $subscribers ) ) {
			$subscribers = [];
		}

		if ( in_array( $email, $subscribers, true ) ) {
			wp_send_json_error( esc_html__( 'This email is already subscribed.', 'novacore' ) );
		}

		$subscribers[] = $email;
		update_option( 'novacore_subscribers', $subscribers );

		wp_send_json_success( esc_html__( 'Thank you! You have been subscribed successfully.', 'novacore' ) );
	}
}
