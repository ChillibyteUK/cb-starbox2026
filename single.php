<?php
/**
 * Template for displaying single posts.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="split-page blog">
	<div class="container-xl pb-5">
		<div class="row g-5">
			<div class="col-lg-5 order-lg-2">
				<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'split-image' ) ); ?> 
			</div>
			<div class="col-lg-7 order-lg-1">
				<div class="container">
					<div class="d-flex flex-wrap justify-content-between align-items-center" style="padding-bottom: 80px; font-size: 0.8rem; color: var(--col-star-blue);">
						<div class="d-flex align-items-center gap-2">
							<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/arrow_back.png' ); ?>" alt="Back" width="10"> 	
							<a href="/blog/" style="text-decoration:none;">All News</a>
						</div>
						<div>Published / <?= esc_html( get_the_date( 'j F, Y' ) ); ?></div>
					</div>
					<h1 style="padding-bottom: 80px;"><?= esc_html( get_the_title() ); ?></h1>
				</div>
				<?php
				the_content();

				$author_id = get_field( 'author', get_the_ID() );

				if ( $author_id ) {
					?>
				<div class="container">
					<div class="author">Author / 
						<a href="<?= esc_url( get_permalink( $author_id ) ); ?>">
							<?= esc_html( get_the_title( $author_id ) ); ?>
						</a>
					</div>
				</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<section class="blog-related">
		<div class="container">
			<div class="blog-related__heading">More articles</div>
			<?php
			// get latest 6 posts excluding current post.
			$args = array(
				'post_type'      => 'post',
				'post_status'    => array( 'publish' ),
				'orderby'        => 'date',
				'order'          => 'DESC', // Descending order.
				'posts_per_page' => 6,    // Get 6 posts.
				'post__not_in'   => array( get_the_ID() ), // Exclude current post.
			);
			$q = new WP_Query( $args );

			if ( $q->have_posts() ) {
				echo '<div class="row g-5">';
				while ( $q->have_posts() ) {
					$q->the_post();
					$post_title = get_field( 'card_title', get_the_ID() ) ? get_field( 'card_title', get_the_ID() ) : get_the_title( get_the_ID() );
					?>
			<div class="col-md-6">
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
			</div>
					<?php
				}
				echo '</div>';
				wp_reset_postdata();
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();

