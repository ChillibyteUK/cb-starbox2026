<?php
/**
 * Block template for CB Service Nav.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$services_page     = get_page_by_path( 'services' );
$service_child_ids = array();

if ( $services_page instanceof WP_Post ) {
	$service_child_ids = get_posts(
		array(
			'post_type'      => 'page',
			'post_parent'    => $services_page->ID,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);
}

?>
<section class="service-nav">
	<?php
	if ( ! empty( $service_child_ids ) ) {
		$n = 1;
		foreach ( $service_child_ids as $service_child_id ) {
			$service_title = esc_html( get_the_title( $service_child_id ) );
			?>
			<div class="service-nav__item" data-page-id="<?= esc_attr( $service_child_id ); ?>">
				<button data-bs-toggle="collapse" data-bs-target="#serviceNavItem<?= esc_attr( $n ); ?>" aria-expanded="false" aria-controls="serviceNavItem<?= esc_attr( $n ); ?>">
					<div class="container">
						<div class="row align-items-center justify-content-start">
							<div class="col-md-2 service-nav__item-number">[
								<div class="text-swap">
									<span class="text-swap__mask">
										<span class="text-swap__inner">
											<span class="text-swap__text"><?= esc_html( sprintf( '%02d', $n ) ); ?></span>
											<span class="text-swap__text" aria-hidden="true"><?= esc_html( sprintf( '%02d', $n ) ); ?></span>
										</span>
									</span>
								</div>
								]</div>
							<div class="col-md-9">
								<div class="service-nav__item-title">
									<h2 class="text-swap" aria-label="<?= esc_attr( $service_title ); ?>">
										<span class="text-swap__mask">
											<span class="text-swap__inner">
												<span class="text-swap__text"><?= esc_html( $service_title ); ?></span>
												<span class="text-swap__text" aria-hidden="true"><?= esc_html( $service_title ); ?></span>
											</span>
										</span>
									</h2>
								</div>
							</div>
							<div class="col-md-1">+</div>
						</div>
					</div>
				</button>
				<div class="collapse" id="serviceNavItem<?= esc_attr( $n ); ?>">
					<div class="container py-5">
						<div class="row g-5">
							<div class="col-md-2">Overview</div>
							<div class="col-md-5 service-nav__item-intro">
								<div class="mb-4"><?= wp_kses_post( get_field( 'intro', $service_child_id ) ); ?></div>
								<a href="<?= esc_url( get_permalink( $service_child_id ) ); ?>" class="btn btn-link">Tell Me More</a>
							</div>
							<div class="col-md-3 offset-md-2 service-nav__item-bullets"><?= wp_kses_post( cb_list( get_field( 'bullets', $service_child_id ) ) ); ?></div>
						</div>
					</div>
				</div>
			</div>
			<?php
			++$n;
		}
	}
	?>
</section>