<?php
/**
 * NovaCore Admin Dashboard
 *
 * @package NovaCore
 * @since 1.0.0
 */

declare(strict_types=1);

namespace NovaCore\Core;

defined( 'ABSPATH' ) || exit;

class Dashboard {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
	}

	public function add_admin_menu(): void {
		add_theme_page(
			esc_html__( 'novacore', 'novacore' ),
			esc_html__( 'novacore', 'novacore' ),
			'manage_options',
			'novacore',
			[ $this, 'render_dashboard' ],
			3
		);

		add_theme_page(
			esc_html__( 'Demo Importer', 'novacore' ),
			esc_html__( 'Demo Importer', 'novacore' ),
			'manage_options',
			'novacore-demo-import',
			[ $this, 'render_demo_import' ]
		);
	}

	public function enqueue_assets( string $hook ): void {
		if ( ! in_array( $hook, [ 'appearance_page_novacore', 'appearance_page_novacore-demo-import' ], true ) ) {
			return;
		}
		wp_enqueue_style( 'novacore-admin' );
		wp_enqueue_script( 'novacore-admin' );
		wp_localize_script( 'novacore-admin', 'novacoreAdmin', [
			'nonce' => wp_create_nonce( 'novacore_nonce' ),
		] );
	}

	public function admin_body_class( string $classes ): string {
		$classes .= ' novacore-admin';
		return $classes;
	}

	public function render_dashboard(): void {
		$theme = wp_get_theme();
		?>
		<div class="wrap novacore-welcome">
			<div class="novacore-welcome__header">
				<div class="novacore-welcome__logo">NC</div>
				<div class="novacore-welcome__info">
					<h1 class="novacore-welcome__title"><?php echo esc_html( $theme->get( 'Name' ) ); ?></h1>
					<p class="novacore-welcome__version">
						<?php
						printf(
							/* translators: %s: theme version */
							esc_html__( 'Version %s', 'novacore' ),
							esc_html( $theme->get( 'Version' ) )
						);
						?>
					</p>
				</div>
				<div class="novacore-welcome__actions">
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"
						class="button button-primary">
						<?php esc_html_e( 'Customize', 'novacore' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=novacore-demo-import' ) ); ?>"
						class="button">
						<?php esc_html_e( 'Import Demo', 'novacore' ); ?>
					</a>
				</div>
			</div>

			<div class="novacore-welcome__status">
				<div class="novacore-welcome__status-item is-good">
					<div class="novacore-welcome__status-item-value">
						<?php echo esc_html( PHP_VERSION ); ?>
					</div>
					<div class="novacore-welcome__status-item-label">
						<?php esc_html_e( 'PHP Version', 'novacore' ); ?>
					</div>
				</div>
				<div class="novacore-welcome__status-item is-good">
					<div class="novacore-welcome__status-item-value">
						<?php echo esc_html( $GLOBALS['wp_version'] ); ?>
					</div>
					<div class="novacore-welcome__status-item-label">
						<?php esc_html_e( 'WordPress Version', 'novacore' ); ?>
					</div>
				</div>
				<div class="novacore-welcome__status-item is-good">
					<div class="novacore-welcome__status-item-value">
						<?php echo extension_loaded( 'mbstring' ) ? '✓' : '✗'; ?>
					</div>
					<div class="novacore-welcome__status-item-label">
						<?php esc_html_e( 'mbstring', 'novacore' ); ?>
					</div>
				</div>
				<div class="novacore-welcome__status-item is-good">
					<div class="novacore-welcome__status-item-value">
						<?php echo class_exists( 'DOMDocument' ) ? '✓' : '✗'; ?>
					</div>
					<div class="novacore-welcome__status-item-label">
						<?php esc_html_e( 'DOM', 'novacore' ); ?>
					</div>
				</div>
			</div>

			<div class="novacore-welcome__cards">
				<?php
				$cards = [
					[
						'icon'  => 'dashicons-admin-customizer',
						'title' => __( 'Customize', 'novacore' ),
						'desc'  => __( 'Personalize your site with live preview, colors, typography, layout, and more.', 'novacore' ),
						'link'  => admin_url( 'customize.php' ),
						'label' => __( 'Open Customizer', 'novacore' ),
					],
					[
						'icon'  => 'dashicons-download',
						'title' => __( 'Demo Importer', 'novacore' ),
						'desc'  => __( 'Import professional demo content to get started quickly with a pre-built design.', 'novacore' ),
						'link'  => admin_url( 'themes.php?page=novacore-demo-import' ),
						'label' => __( 'Import Demo', 'novacore' ),
					],
					[
						'icon'  => 'dashicons-admin-plugins',
						'title' => __( 'Recommended Plugins', 'novacore' ),
						'desc'  => __( 'Install recommended plugins to extend functionality like page builder & SEO.', 'novacore' ),
						'link'  => admin_url( 'plugins.php' ),
						'label' => __( 'Manage Plugins', 'novacore' ),
					],
					[
						'icon'  => 'dashicons-book',
						'title' => __( 'Documentation', 'novacore' ),
						'desc'  => __( 'Read the documentation for detailed setup guides and feature overviews.', 'novacore' ),
						'link'  => 'https://novacorewp.com/docs',
						'label' => __( 'View Docs', 'novacore' ),
					],
					[
						'icon'  => 'dashicons-sos',
						'title' => __( 'Support', 'novacore' ),
						'desc'  => __( 'Get help from our support team for any questions or issues you encounter.', 'novacore' ),
						'link'  => 'https://novacorewp.com/support',
						'label' => __( 'Get Support', 'novacore' ),
					],
					[
						'icon'  => 'dashicons-admin-site-alt',
						'title' => __( 'Performance', 'novacore' ),
						'desc'  => __( 'Fine-tune performance settings for optimal loading speed and user experience.', 'novacore' ),
						'link'  => admin_url( 'customize.php?autofocus[panel]=novacore_performance' ),
						'label' => __( 'Optimize', 'novacore' ),
					],
				];

				foreach ( $cards as $card ) :
					?>
				<div class="novacore-welcome__card">
					<div class="novacore-welcome__card-icon">
						<span class="dashicons <?php echo esc_attr( $card['icon'] ); ?>"></span>
					</div>
					<h3 class="novacore-welcome__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="novacore-welcome__card-desc"><?php echo esc_html( $card['desc'] ); ?></p>
					<a href="<?php echo esc_url( $card['link'] ); ?>" class="novacore-welcome__card-link"
						<?php echo strpos( $card['link'], 'http' ) === 0 ? 'target="_blank" rel="noopener"' : ''; ?>>
						<?php echo esc_html( $card['label'] ); ?>
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	public function render_demo_import(): void {
		$demo_import = new Demo_Import();
		$demos = $demo_import->get_demos();
		$structure = $demo_import->get_site_structure_doc();
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'import';
		?>
		<div class="wrap novacore-demo-importer">
			<h1><?php esc_html_e( 'Demo Importer', 'novacore' ); ?></h1>

			<div class="novacore-tabs">
				<nav class="novacore-tabs__nav">
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=novacore-demo-import&tab=import' ) ); ?>"
						class="novacore-tab-btn <?php echo 'import' === $active_tab ? 'active' : ''; ?>"
						data-tab="novacore-tab-import">
						<span class="dashicons dashicons-download"></span>
						<?php esc_html_e( 'Import Demo', 'novacore' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=novacore-demo-import&tab=export' ) ); ?>"
						class="novacore-tab-btn <?php echo 'export' === $active_tab ? 'active' : ''; ?>"
						data-tab="novacore-tab-export">
						<span class="dashicons dashicons-upload"></span>
						<?php esc_html_e( 'Export / Import Settings', 'novacore' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=novacore-demo-import&tab=structure' ) ); ?>"
						class="novacore-tab-btn <?php echo 'structure' === $active_tab ? 'active' : ''; ?>"
						data-tab="novacore-tab-structure">
						<span class="dashicons dashicons-layout"></span>
						<?php esc_html_e( 'Site Structure', 'novacore' ); ?>
					</a>
				</nav>

				<div class="novacore-tabs__content">
					<!-- Import Tab -->
					<div class="novacore-tab-panel <?php echo 'import' === $active_tab ? 'active' : ''; ?>" id="novacore-tab-import">
						<p><?php esc_html_e( 'Select a demo to import. This will import posts, pages, images, and theme settings. It may take a few minutes.', 'novacore' ); ?></p>
						<div class="novacore-demo-importer__list">
							<?php foreach ( $demos as $slug => $demo ) : ?>
							<div class="novacore-demo-importer__item">
								<div class="novacore-demo-importer__item-preview">
									<span class="dashicons dashicons-layout"></span>
								</div>
								<div class="novacore-demo-importer__item-body">
									<h3 class="novacore-demo-importer__item-title">
										<?php echo esc_html( $demo['name'] ); ?>
									</h3>
									<p class="novacore-demo-importer__item-desc">
										<?php echo esc_html( $demo['description'] ); ?>
									</p>
									<button class="button button-primary novacore-import-demo"
										data-demo="<?php echo esc_attr( $slug ); ?>">
										<?php esc_html_e( 'Import Demo', 'novacore' ); ?>
									</button>
								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Export Tab -->
					<div class="novacore-tab-panel <?php echo 'export' === $active_tab ? 'active' : ''; ?>" id="novacore-tab-export">
						<div class="novacore-settings-tools">
							<div class="novacore-settings-tool">
								<h2><?php esc_html_e( 'Export Settings', 'novacore' ); ?></h2>
								<p><?php esc_html_e( 'Download your current theme settings (Customizer mods, widgets, menus, homepage configuration) as a JSON file. Use this to backup or transfer your setup.', 'novacore' ); ?></p>
								<button class="button button-primary novacore-export-settings">
									<span class="dashicons dashicons-download"></span>
									<?php esc_html_e( 'Download Settings', 'novacore' ); ?>
								</button>
							</div>

							<div class="novacore-settings-tool">
								<h2><?php esc_html_e( 'Import Settings', 'novacore' ); ?></h2>
								<p><?php esc_html_e( 'Upload a previously exported settings JSON file to restore theme configuration.', 'novacore' ); ?></p>
								<form class="novacore-import-form" enctype="multipart/form-data">
									<input type="file" name="settings_file" accept=".json" required>
									<button type="submit" class="button button-primary">
										<span class="dashicons dashicons-upload"></span>
										<?php esc_html_e( 'Upload & Import', 'novacore' ); ?>
									</button>
								</form>
							</div>
						</div>
					</div>

					<!-- Structure Tab -->
					<div class="novacore-tab-panel <?php echo 'structure' === $active_tab ? 'active' : ''; ?>" id="novacore-tab-structure">
						<h2><?php esc_html_e( 'Site Structure', 'novacore' ); ?></h2>
						<p><?php esc_html_e( 'Overview of the current NovaCore theme structure and components.', 'novacore' ); ?></p>

						<div class="novacore-structure">
							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Header', 'novacore' ); ?></h3>
								<pre class="novacore-structure__diagram"><?php
									echo esc_html( "┌─────────────────────────────────────────────────┐\n" .
										"│  Top Bar (green)                                   │\n" .
										"│  [nav toggle] [home link] [search] [dark mode]     │\n" .
										"├─────────────────────────────────────────────────┤\n" .
										"│  Brand Section                                    │\n" .
										"│  ┌──────────────┐          ┌──────────────────┐  │\n" .
										"│  │ Logo/Image   │          │  Ad 1 (728×90)  │  │\n" .
										"│  └──────────────┘          └──────────────────┘  │\n" .
										"└─────────────────────────────────────────────────┘" );
								?></pre>
								<table class="novacore-structure__table">
									<?php foreach ( $structure['header']['components'] as $key => $desc ) : ?>
									<tr><td><strong><?php echo esc_html( $key ); ?></strong></td><td><?php echo esc_html( $desc ); ?></td></tr>
									<?php endforeach; ?>
								</table>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Footer', 'novacore' ); ?></h3>
								<pre class="novacore-structure__diagram"><?php
									echo esc_html( "┌─────────────────────────────────────────────────┐\n" .
										"│  Top Bar                                         │\n" .
										"│  [social icons]               [footer menu]      │\n" .
										"├─────────────────────────────────────────────────┤\n" .
										"│  Widget Row (1–4 columns)                        │\n" .
										"├─────────────────────────────────────────────────┤\n" .
										"│  Bottom Bar                                      │\n" .
										"│  © Site Name · Powered by NovaCore · Tagline     │\n" .
										"└─────────────────────────────────────────────────┘" );
								?></pre>
								<table class="novacore-structure__table">
									<?php foreach ( $structure['footer']['components'] as $key => $desc ) : ?>
									<tr><td><strong><?php echo esc_html( $key ); ?></strong></td><td><?php echo esc_html( $desc ); ?></td></tr>
									<?php endforeach; ?>
								</table>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Layouts', 'novacore' ); ?></h3>
								<table class="novacore-structure__table">
									<?php foreach ( $structure['layouts'] as $key => $desc ) : ?>
									<tr><td><strong><?php echo esc_html( ucfirst( $key ) ); ?></strong></td><td><?php echo esc_html( $desc ); ?></td></tr>
									<?php endforeach; ?>
								</table>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Custom Blocks', 'novacore' ); ?></h3>
								<?php if ( ! empty( $structure['custom_blocks'] ) ) : ?>
								<table class="novacore-structure__table">
									<thead><tr><th><?php esc_html_e( 'Block', 'novacore' ); ?></th><th><?php esc_html_e( 'Description', 'novacore' ); ?></th></tr></thead>
									<tbody>
									<?php foreach ( $structure['custom_blocks'] as $block ) : ?>
									<tr><td><code><?php echo esc_html( $block['name'] ); ?></code></td><td><?php echo esc_html( $block['description'] ?: $block['title'] ); ?></td></tr>
									<?php endforeach; ?>
									</tbody>
								</table>
								<?php else : ?>
								<p><?php esc_html_e( 'No custom blocks registered.', 'novacore' ); ?></p>
								<?php endif; ?>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Ad Placements', 'novacore' ); ?></h3>
								<table class="novacore-structure__table">
									<thead><tr><th><?php esc_html_e( 'Area', 'novacore' ); ?></th><th><?php esc_html_e( 'Location', 'novacore' ); ?></th></tr></thead>
									<tbody>
									<?php foreach ( $structure['ad_placements'] as $key => $desc ) : ?>
									<tr><td><strong><?php echo esc_html( $key ); ?></strong></td><td><?php echo esc_html( $desc ); ?></td></tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Registered Sidebars', 'novacore' ); ?></h3>
								<table class="novacore-structure__table">
									<thead><tr><th><?php esc_html_e( 'ID', 'novacore' ); ?></th><th><?php esc_html_e( 'Name', 'novacore' ); ?></th></tr></thead>
									<tbody>
									<?php foreach ( $structure['sidebars'] as $id => $sidebar ) : ?>
									<tr><td><code><?php echo esc_html( $id ); ?></code></td><td><?php echo esc_html( $sidebar['name'] ); ?></td></tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<div class="novacore-structure__section">
								<h3><?php esc_html_e( 'Nav Menu Locations', 'novacore' ); ?></h3>
								<table class="novacore-structure__table">
									<thead><tr><th><?php esc_html_e( 'Location', 'novacore' ); ?></th><th><?php esc_html_e( 'Description', 'novacore' ); ?></th></tr></thead>
									<tbody>
									<?php foreach ( $structure['nav_menus'] as $location => $desc ) : ?>
									<tr><td><code><?php echo esc_html( $location ); ?></code></td><td><?php echo esc_html( $desc ); ?></td></tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
