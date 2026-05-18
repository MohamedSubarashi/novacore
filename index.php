<?php
/**
 * Main Index Template
 *
 * @package NovaCore
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$featured_ids = [];

?>

<div class="novacore-container">

	<?php if ( is_home() && ! is_paged() ) : ?>
	<!-- Featured Posts Grid - Before Slider -->
	<?php
	$home_grid = new WP_Query( [
		'posts_per_page'      => 4,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	] );
	if ( $home_grid->have_posts() ) :
		?>
	<div class="novacore-home-grid">
		<div class="novacore-container">
			<div class="novacore-home-grid__inner">
				<?php while ( $home_grid->have_posts() ) : $home_grid->the_post(); ?>
				<article class="novacore-home-grid__post">
					<a href="<?php the_permalink(); ?>" class="novacore-home-grid__thumb">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'novacore-sm' ); ?>
						<?php endif; ?>
					</a>
					<div class="novacore-home-grid__body">
						<?php
						$cats = get_the_category();
						if ( ! empty( $cats ) ) :
							?>
						<span class="novacore-home-grid__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
						<?php endif; ?>
						<h3 class="novacore-home-grid__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<span class="novacore-home-grid__date"><?php echo esc_html( get_the_date() ); ?></span>
					</div>
				</article>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
		<?php
		wp_reset_postdata();
	endif;
	?>

	<!-- Featured Slider - Hero -->
	<?php
	$slideshow_show = get_theme_mod( 'novacore_slideshow_show', true );
	if ( $slideshow_show ) :
		$slideshow_source = get_theme_mod( 'novacore_slideshow_source', 'recent' );
		$slideshow_cat   = (int) get_theme_mod( 'novacore_slideshow_category', 0 );
		$slideshow_count = (int) get_theme_mod( 'novacore_slideshow_count', 5 );
		$slideshow_autoplay = get_theme_mod( 'novacore_slideshow_autoplay', true ) ? '1' : '0';
		$slideshow_speed  = (int) get_theme_mod( 'novacore_slideshow_speed', 5000 );

		$slideshow_args = [
			'posts_per_page'      => $slideshow_count,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		];

		if ( 'category' === $slideshow_source && $slideshow_cat > 0 ) {
			$slideshow_args['cat'] = $slideshow_cat;
		}
	?>
	<div class="novacore-slider novacore-post-slider novacore-post-slider--hero" id="novacore-featured-slider" data-autoplay="<?php echo esc_attr( $slideshow_autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $slideshow_speed ); ?>">
		<div class="novacore-slider__track">
			<?php
			$featured = get_posts( $slideshow_args );
			foreach ( $featured as $i => $post ) :
				setup_postdata( $post );
				$featured_ids[] = $post->ID;
				?>
				<div class="novacore-slider__slide novacore-post-slider__slide<?php echo $i === 0 ? esc_attr( ' is-active' ) : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>">
					<article class="novacore-post-slider__article">
						<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
						<div class="novacore-post-slider__bg">
							<?php echo get_the_post_thumbnail( $post->ID, 'novacore-xl', [ 'loading' => $i === 0 ? 'eager' : 'lazy' ] ); ?>
						</div>
						<?php endif; ?>
						<div class="novacore-post-slider__overlay">
							<div class="novacore-post-slider__content">
								<?php
								$cats = get_the_category( $post->ID );
								if ( ! empty( $cats ) ) :
									?>
								<span class="novacore-post-slider__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
								<?php endif; ?>
								<h3 class="novacore-post-slider__title">
									<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo esc_html( get_the_title( $post->ID ) ); ?></a>
								</h3>
								<span class="novacore-post-slider__date"><?php echo esc_html( get_the_date( '', $post->ID ) ); ?></span>
							</div>
						</div>
					</article>
				</div>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<button class="novacore-slider__arrow novacore-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'novacore' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
		</button>
		<button class="novacore-slider__arrow novacore-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'novacore' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
		</button>
		<div class="novacore-slider__dots" role="tablist">
			<?php foreach ( $featured as $index => $post ) : ?>
				<button class="novacore-slider__dot<?php echo $index === 0 ? esc_attr( ' is-active' ) : ''; ?>" data-slide="<?php echo esc_attr( $index ); ?>" role="tab" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'novacore' ), $index + 1 ) ); ?>"></button>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>

	<?php $ad_2 = get_theme_mod( 'novacore_ad_2_code' ); ?>
	<?php if ( $ad_2 ) : ?>
	<div class="novacore-ad-area novacore-ad-area--below-slider">
		<div class="novacore-container">
			<?php echo wp_kses_post( $ad_2 ); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="novacore-layout novacore-layout--home">
		<div class="novacore-content-area">
		<main id="main" class="novacore-main">

		<?php novacore_ad_area( 'before-content' ); ?>

		<?php if ( is_home() && ! is_paged() ) : ?>

		<!-- Magazine Featured Grid #1 -->
		<?php
		$mag_grid_1_show  = get_theme_mod( 'novacore_mag_grid_1_show', true );
		$mag_grid_1_title = get_theme_mod( 'novacore_mag_grid_1_title', esc_html__( 'Editor\'s Pick', 'novacore' ) );
		$mag_grid_1_count = max( 2, (int) get_theme_mod( 'novacore_mag_grid_1_count', 3 ) );

		$mag_ids = [];
		if ( $mag_grid_1_show ) :
			$mag_query_1 = new WP_Query( [
				'posts_per_page'      => $mag_grid_1_count,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'post__not_in'        => $featured_ids,
				'no_found_rows'       => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			] );
			$mag_posts_1 = [];
			if ( $mag_query_1->have_posts() ) :
				while ( $mag_query_1->have_posts() ) :
					$mag_query_1->the_post();
					$mag_posts_1[] = [
						'id'        => get_the_ID(),
						'thumbnail' => get_the_post_thumbnail( get_the_ID(), 'novacore-lg' ),
						'cats'      => get_the_category(),
						'title'     => get_the_title(),
						'permalink' => get_permalink(),
						'date'      => get_the_date(),
						'author'    => get_the_author(),
					];
				endwhile;
				wp_reset_postdata();
			endif;

			if ( ! empty( $mag_posts_1 ) && function_exists( 'novacore_magazine_grid' ) ) {
				$ids = novacore_magazine_grid( $mag_posts_1, $mag_grid_1_title );
				$mag_ids = array_merge( $mag_ids, $ids );
			}
		endif;

		do_action( 'novacore_after_magazine_grid' ); ?>

		<!-- Category Section Blocks -->
		<?php
		$exclude_ids = array_merge( $featured_ids, $mag_ids );
		$category_sections = apply_filters( 'novacore_home_category_sections', [] );
		if ( empty( $category_sections ) ) {
			$all_cats = get_categories( [ 'number' => 3, 'orderby' => 'count', 'order' => 'DESC', 'exclude' => [ 1 ] ] );
			foreach ( $all_cats as $cat ) {
				$category_sections[] = $cat->term_id;
			}
		}

		foreach ( $category_sections as $cat_id ) :
			$cat_obj = get_category( $cat_id );
			if ( is_wp_error( $cat_obj ) || ! $cat_obj ) {
				continue;
			}

			$cat_query = new WP_Query( [
				'cat'                 => $cat_id,
				'posts_per_page'      => 4,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'post__not_in'        => $exclude_ids,
				'no_found_rows'       => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			] );

			if ( ! $cat_query->have_posts() ) {
				continue;
			}
			?>
			<section class="novacore-cat-section">
				<div class="novacore-cat-section__header">
					<h2 class="novacore-cat-section__title">
						<a href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>">
							<?php echo esc_html( $cat_obj->name ); ?>
						</a>
					</h2>
					<?php
					$sub_cats = get_categories( [ 'parent' => $cat_id, 'number' => 4 ] );
					if ( ! empty( $sub_cats ) ) :
						?>
					<div class="novacore-cat-section__tabs">
						<?php foreach ( $sub_cats as $sub ) : ?>
							<a href="<?php echo esc_url( get_category_link( $sub->term_id ) ); ?>" class="novacore-cat-section__tab">
								<?php echo esc_html( $sub->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
				<div class="novacore-cat-section__grid">
					<?php
					while ( $cat_query->have_posts() ) :
						$cat_query->the_post();
						?>
						<article id="cat-post-<?php the_ID(); ?>" <?php post_class( 'novacore-cat-post' ); ?>>
							<a href="<?php the_permalink(); ?>" class="novacore-cat-post__thumbnail">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'novacore-md' ); ?>
								<?php endif; ?>
							</a>
							<h3 class="novacore-cat-post__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<span class="novacore-cat-post__date"><?php echo esc_html( get_the_date() ); ?></span>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endforeach; ?>

		<!-- Latest Posts -->
		<section class="novacore-latest-posts">
			<header class="novacore-page-header">
				<h2 class="novacore-page-title">
					<?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: __( 'Latest News', 'novacore' ) ); ?>
				</h2>
			</header>

			<div class="novacore-posts novacore-posts--list" id="novacore-posts-container">
		<?php
		$paged        = max( 1, get_query_var( 'paged' ) );
		$latest_query = new WP_Query( [
			'post__not_in'        => $exclude_ids,
			'posts_per_page'      => 6,
			'paged'               => $paged,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		] );
		if ( $latest_query->have_posts() ) :
			while ( $latest_query->have_posts() ) :
				$latest_query->the_post();
				novacore_content_template();
			endwhile;
			wp_reset_postdata();
		endif;
		?>
			</div>
			<?php novacore_pagination( $latest_query ); ?>
		</section>

		<!-- Magazine Featured Grid #2 (below posts) -->
		<?php
		$mag_grid_2_show  = get_theme_mod( 'novacore_mag_grid_2_show', true );
		$mag_grid_2_title = get_theme_mod( 'novacore_mag_grid_2_title', esc_html__( 'Must Read', 'novacore' ) );
		$mag_grid_2_count = max( 2, (int) get_theme_mod( 'novacore_mag_grid_2_count', 3 ) );

		if ( $mag_grid_2_show ) :
			$mag_query_2 = new WP_Query( [
				'posts_per_page'      => $mag_grid_2_count,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'post__not_in'        => array_merge( $featured_ids, $mag_ids, $exclude_ids ),
				'no_found_rows'       => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			] );
			$mag_posts_2 = [];
			if ( $mag_query_2->have_posts() ) :
				while ( $mag_query_2->have_posts() ) :
					$mag_query_2->the_post();
					$mag_posts_2[] = [
						'id'        => get_the_ID(),
						'thumbnail' => get_the_post_thumbnail( get_the_ID(), 'novacore-lg' ),
						'cats'      => get_the_category(),
						'title'     => get_the_title(),
						'permalink' => get_permalink(),
						'date'      => get_the_date(),
						'author'    => get_the_author(),
					];
				endwhile;
				wp_reset_postdata();
			endif;

			if ( ! empty( $mag_posts_2 ) && function_exists( 'novacore_magazine_grid' ) ) {
				novacore_magazine_grid( $mag_posts_2, $mag_grid_2_title );
			}
		endif;
		?>

		<?php elseif ( is_home() && is_paged() ) : ?>

		<header class="novacore-page-header">
			<h1 class="novacore-page-title">
				<?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: __( 'Latest News', 'novacore' ) ); ?>
			</h1>
		</header>

		<div class="novacore-posts novacore-posts--list" id="novacore-posts-container">
			<?php
			while ( have_posts() ) :
				the_post();
				novacore_content_template();
			endwhile;
			?>
		</div>

		<?php
		$infinite_scroll = get_theme_mod( 'novacore_blog_infinite_scroll', false );
		if ( ! $infinite_scroll ) {
			novacore_pagination();
		} else {
			echo '<div class="novacore-infinite-scroll-trigger"></div>';
		}
		?>
		<?php elseif ( is_archive() || is_search() ) : ?>

		<?php if ( is_archive() ) : ?>
		<header class="novacore-page-header">
			<?php
			the_archive_title( '<h1 class="novacore-page-title">', '</h1>' );
			the_archive_description( '<div class="novacore-archive-description">', '</div>' );
			?>
		</header>
		<?php endif; ?>

		<?php if ( is_search() ) : ?>
		<header class="novacore-page-header">
			<h1 class="novacore-page-title">
				<?php
				printf(
					esc_html__( 'Search Results for: %s', 'novacore' ),
					'<span>' . get_search_query() . '</span>'
				);
				?>
			</h1>
		</header>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
		<div class="novacore-posts novacore-posts--list" id="novacore-posts-container">
			<?php
			while ( have_posts() ) :
				the_post();
				novacore_content_template();
			endwhile;
			?>
		</div>
		<?php
		$infinite_scroll = get_theme_mod( 'novacore_blog_infinite_scroll', false );
		if ( ! $infinite_scroll ) {
			novacore_pagination();
		} else {
			echo '<div class="novacore-infinite-scroll-trigger"></div>';
		}
		?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>

		<?php else : ?>

		<?php if ( have_posts() ) : ?>
		<div class="novacore-posts novacore-posts--list" id="novacore-posts-container">
			<?php
			while ( have_posts() ) :
				the_post();
				novacore_content_template();
			endwhile;
			?>
		</div>
		<?php novacore_pagination(); ?>
		<?php else : ?>
		<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>

		<?php endif; ?>

		<?php novacore_ad_area( 'after-content' ); ?>

		</main>
		</div>

		<div class="novacore-sidebar-area">
		<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
