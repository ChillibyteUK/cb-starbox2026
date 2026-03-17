<?php
/**
 * Block template for CB Latest News.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="latest-news">
	<div class="container">
		<h2 class="text-center has-white-color">Starbox News</h2>

		<div class="latest-news__heading">Recent articles</div>
		<div class="row g-5">
			<div class="col-md-6">
				<?php
				// get 3 most recent posts
				$q = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 3,
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);
				if ( $q->have_posts() ) {
					while ( $q->have_posts() ) {
						$q->the_post();
						$post_title = get_field( 'card_title' ) ? get_field( 'card_title' ) : get_the_title();
						?>
						<a class="blog-related__link" href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
							<div class="blog-related__dot">
								<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="4" cy="4" r="4" fill="#EDEDED"/>
								</svg>
							</div>
							<div class="blog-related__meta">
								<div class="text-swap" aria-label="Published <?= esc_attr( get_the_date( 'j F, Y', get_the_ID() ) ); ?>">
									<span class="text-swap__mask">
										<span class="text-swap__inner">
											<span class="text-swap__text">Published <?= esc_html( get_the_date( 'j F, Y', get_the_ID() )  ); ?></span>
											<span class="text-swap__text" aria-hidden="true">Published <?= esc_html( get_the_date( 'j F, Y', get_the_ID() ) ); ?></span>
										</span>
									</span>
								</div>
							</div>
							<div class="blog-related__title"><?= esc_html( $post_title ); ?></div>
							<div class="blog-related__arrow">
								<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2 2L18 18M18 18V2M18 18H2" stroke="CurrentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="blog-related__line">
								<div class="blog-related__line-line"></div>
							</div>
						</a>
						<?php
					}
					wp_reset_postdata();
				}
				?>
			</div>
			<div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/Energy-Cube@1045x887.webp' ); ?>">
			</div>
		</div>
	</div>
</section>