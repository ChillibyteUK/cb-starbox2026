<?php
/**
 * Template for displaying the blog index page.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );

get_header();
?>
<main id="main" class="news-insights">
	<?php
	echo wp_kses_post(
		apply_filters(
			'the_content',
			$page_for_posts ? get_post_field( 'post_content', $page_for_posts ) : ''
		)
	);
	?>
	<section class="blog-index py-5">
		<div class="container">
			<div class="row g-4">
				<?php
				$args = array(
					'post_type'      => 'post',
					'post_status'    => array( 'publish' ),
					'orderby'        => 'date',
					'order'          => 'DESC', // Descending order.
					'posts_per_page' => -1,    // Get all posts.
				);
				$q = new WP_Query( $args );

				if ( $q->have_posts() ) {
					while ( $q->have_posts() ) {
						$q->the_post();
						?>
				<div class="col-md-6 col-lg-4">
					<a href="<?php echo esc_url( get_permalink() ); ?>" class="blog-index__card">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div class="blog-index__date">Published <?php echo esc_html( get_the_date( 'j F, Y' ) ); ?></div>
							<div class="blog-card-arrow">
								<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
									<path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" fill="currentColor"></path>
								</svg>
							</div>
						</div>
						<div class="blog-index__image-wrapper">
							<?php
							if ( get_the_post_thumbnail( get_the_ID() ) ) {
								echo get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'blog-index__image', 'alt' => get_post_meta( get_post_thumbnail_id( get_the_ID() ), '_wp_attachment_image_alt', true ) ) );
							} else {
								echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/img/default-post-image.png' ) . '" alt="" class="insight-type-grid__image" />';
							}
							?>
						</div>
						<div class="blog-index__title">
							<?php
							$post_title = get_field( 'card_title' ) ? get_field( 'card_title' ) : get_the_title();
							?>
							<h3 class="text-swap" aria-label="<?= esc_attr( $post_title ); ?>">
								<span class="text-swap__mask">
									<span class="text-swap__inner">
										<span class="text-swap__text"><?= esc_html( $post_title ); ?></span>
										<span class="text-swap__text" aria-hidden="true"><?= esc_html( $post_title ); ?></span>
									</span>
								</span>
							</h3>
						</div>
					</a>
				</div>
						<?php
					}
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php

	get_template_part( 'blocks/cb-testimonials' );

	?>
</main>
<?php
get_footer();
?>