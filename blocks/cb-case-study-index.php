<?php
/**
 * Block template for CB Case Study Index.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$render_rail_group = static function ( array $image_ids, string $group_class, string $tile_class ) {
	?>
	<div class="<?php echo esc_attr( $group_class ); ?>">
		<?php foreach ( $image_ids as $image_id ) : ?>
			<div class="<?php echo esc_attr( $tile_class ); ?>">
				<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'case-study-index__tile-image', 'loading' => 'lazy', 'alt' => '' ) ); ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
};

$render_rail_track = static function ( array $image_ids, string $rail_class, string $track_class, string $tile_class ) use ( $render_rail_group ) {
	?>
	<div class="<?php echo esc_attr( $rail_class ); ?>" aria-hidden="true">
		<div class="<?php echo esc_attr( $track_class ); ?>">
			<?php $render_rail_group( $image_ids, 'case-study-index__rail-group', $tile_class ); ?>
			<?php $render_rail_group( $image_ids, 'case-study-index__rail-group', $tile_class ); ?>
		</div>
	</div>
	<?php
};

?>
<section class="case-study-index">
	<div class="container">
		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'case_study',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$rail_up   = get_field( 'rail_images_up', get_the_ID() );
				$rail_down = get_field( 'rail_images_down', get_the_ID() );
				$has_rails = is_array( $rail_up ) && is_array( $rail_down ) && 2 === count( $rail_up ) && 2 === count( $rail_down );
				?>
		<div class="case-study-index__entry">
			<div class="case-study-index__layout<?php echo $has_rails ? '' : ' case-study-index__layout--no-rails'; ?>">
				<a class="case-study-index__link" href="<?php the_permalink(); ?>">
					<h2 class="case-study-index__title"><?php the_title(); ?></h2>
				</a>
				<?php
				if ( $has_rails ) {
					$render_rail_track( $rail_up, 'case-study-index__rail case-study-index__rail--up', 'case-study-index__track case-study-index__track--up', 'case-study-index__tile' );
					$render_rail_track( $rail_down, 'case-study-index__rail case-study-index__rail--down', 'case-study-index__track case-study-index__track--down', 'case-study-index__tile case-study-index__tile--offset' );
				}
				?>
			</div>
		</div>
				<?php
			}
			wp_reset_postdata();
		}
		?>
	</div>
</section>