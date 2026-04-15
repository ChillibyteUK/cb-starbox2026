<?php
/**
 * Template for displaying Single Case Study
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>
<main id="main" class="split-page case-study">
	<div class="container pb-5">
		<p class="case-study__marquee">StarBox Professional Services Showcase</p>
		<div class="d-flex align-items-center flex-wrap gap-3 mb-5">
			<?php
			if ( get_field( 'alt_logo', get_the_ID() ) ) {
				echo wp_get_attachment_image( get_field( 'alt_logo', get_the_ID() ), 'full', false, array( 'class' => 'case-study__title-image' ) );
			} elseif ( get_field( 'quote', get_the_ID() )[0] ) {
				echo get_the_post_thumbnail( get_field( 'quote', get_the_ID() )[0], 'full', array( 'class' => 'case-study__title-image' ) );
			}
			?>
			<h1 class="case-study__title"><?= esc_html( get_the_title() ); ?></h1>
		</div>
		<div class="case-study__intro">
			<?= wp_kses_post( get_field( 'intro', get_the_ID() ) ); ?>
		</div>
		<div class="case-study__meta">
			<?= cb_list( get_field( 'meta', get_the_ID() ), array( 'item_tag' => 'span' ) ); ?>
		</div>
		<?= wp_get_attachment_image( get_field( 'banner_image', get_the_ID() ), 'full', false, array( 'class' => 'case-study__banner' ) ); ?>
		<div class="case-study__quote">
			<?= wp_kses_post( get_the_content( null, false, get_field( 'quote', get_the_ID() )[0] ) ); ?>
			<div class="case-study__quote-author"><?= esc_html( get_the_title( get_field( 'quote', get_the_ID() )[0] ) ); ?></div>
		</div>
		<div class="row g-5" style="padding-top: 5.5rem">
			<div class="col-lg-4">
				<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'split-image' ) ); ?> 
			</div>
			<div class="col-lg-8">
				<?php
				the_content();

				if ( get_field( 'services', get_the_ID() ) ) {
					echo '<div class="case-study__services-title mb-3">StarBox services we provide for ' . get_the_title() . '</div>';
					echo '<div class="case-study__services">';
					foreach ( get_field( 'services', get_the_ID() ) as $service ) {
						echo '<a href="'. get_the_permalink( $service ) . '" class="case-study__service"><span class="tt">' . esc_html( get_the_title( $service ) ) . '</span><span class="sm">View service</span></a>';
					}
					echo '</div>';
				}
				?>

			</div>
		</div>
		<h2 class="large-heading text-center" style="padding-top:5rem">More...</h2>
		<div class="text-center">See more of our work with our clients.</div>
		<?php
// select three case studies excluding current one.
$q = new WP_Query(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => 3,
		'post__not_in'   => array( get_the_ID() ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	));
if ( $q->have_posts() ) {
	echo '<div class="case-study__more row g-4 mt-4">';
	while ( $q->have_posts() ) {
		$q->the_post();
		?>
<div class="col-md-4">
	<a href="<?= get_the_permalink( get_the_ID() ); ?>" class="case-study__more-card">
		<?php the_post_thumbnail( 'full', array( 'class' => 'case-study__more-image' ) ); ?>
		<div class="case-study__more-title"><?= esc_html( get_the_title() ); ?></div>
	</a>
</div>
		<?php
	}
}
	?>
	</div>
</main>
<?php
get_footer();