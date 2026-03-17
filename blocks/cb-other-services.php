<?php
/**
 * Block template for CB Other Services.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$current_page_id = get_queried_object_id();
$services_page   = get_page_by_path( 'services' );

$service_children = array();

if ( $services_page instanceof WP_Post ) {
	$service_children = get_pages(
		array(
			'post_type'   => 'page',
			'parent'      => (int) $services_page->ID,
			'sort_column' => 'menu_order,post_title',
			'sort_order'  => 'ASC',
			'exclude'     => array( (int) $current_page_id ),
		)
	);
}
?>
<section class="other-services">
	<p class="h2 mb-5">We are the original accountants for influencers, YouTubers, content creators, and streamers.</p>
	<p class="fs-500 mb-4">Our other services</p>
	<?php
	if ( ! empty( $service_children ) ) {
		foreach ( $service_children as $service_child ) {
			?>
			<a class="other-services__link" href="<?php echo esc_url( get_permalink( $service_child->ID ) ); ?>">
				<div class="other-services__dot">
					<svg width="16" height="16" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="4" cy="4" r="4" fill="#EDEDED"/>
					</svg>
				</div>
				<div class="other-services__meta">
					<div class="text-swap" aria-label="<?= esc_attr( get_the_title( $service_child->ID ) ); ?>">
						<span class="text-swap__mask">
							<span class="text-swap__inner">
								<span class="text-swap__text"><?= esc_html( get_the_title( $service_child->ID ) ); ?></span>
								<span class="text-swap__text" aria-hidden="true"><?= esc_html( get_the_title( $service_child->ID ) ); ?></span>
							</span>
						</span>
					</div>
				</div>
				<div class="other-services__title">
					<?php
					// get first h1 from page.
					$h1      = '';
					$content = get_post_field( 'post_content', $service_child->ID );
					if ( preg_match( '/<h1[^>]*>(.*?)<\/h1>/', $content, $matches ) ) {
						$h1 = wp_strip_all_tags( $matches[1] );
					}
					if ( $h1 ) {
						echo esc_html( $h1 );
					}
					?>
				</div>
				<div class="other-services__arrow"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/arrow_go.png' ); ?>"></div>
				<div class="other-services__line">
					<div class="other-services__line-line"></div>
				</div>
			</a>
			<?php
		}
	}
	?>
</section>
