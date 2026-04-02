<?php
/**
 * Template Name: Split Page - Services
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="split-page">
	<div class="container pb-5">
		<div class="row g-5">
			<div class="col-lg-5 order-lg-2">
				<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'split-image' ) ); ?> 
			</div>
			<div class="col-lg-7 order-lg-1">
				<div class="container">
					<div class="d-flex flex-wrap justify-content-between align-items-center" style="padding-bottom: 80px; font-size: 0.8rem; color: var(--col-star-blue);">
						<div class="d-flex align-items-center gap-2">
							<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/arrow_back.png' ); ?>" alt="Back" width="10"> 	
							<a href="/services/" style="text-decoration:none;">All services</a>
						</div>
						<div>StarBox Services / <?= esc_html( get_the_title() ); ?></div>
					</div>
				</div>
				<?php
				the_content();

				get_template_part( 'blocks/cb-other-services' );
				?>
			</div>
		</div>
	</div>
	<?php
	// Include testimonials block.
	get_template_part( 'blocks/cb-testimonials' );
	?>
</main>
<?php
get_footer();