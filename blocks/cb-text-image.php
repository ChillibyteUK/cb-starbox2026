<?php
/**
 * Block template for CB Text Image.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$col_order   = get_field( 'order' );
$image_order = ( 'Image' === $col_order ) ? 'order-1 pe-lg-5' : 'order-2';
$text_order  = ( 'Image' === $col_order ) ? 'order-2 ps-lg-5' : 'order-1';

$image_aos = ( 'Image' === $col_order ) ? 'fade-right' : 'fade-left';
$text_aos  = ( 'Image' === $col_order ) ? 'fade-left' : 'fade-right';

$layout      = get_field( 'layout' ) ? get_field( 'layout' ) : '50-50';
$image_width = '50-50' === $layout ? 'col-lg-6' : 'col-lg-4';
$text_width  = '50-50' === $layout ? 'col-lg-6' : 'col-lg-8';

$constrain    = 'no' === get_field( 'constrain_image' ) ? '' : 'image-16x9';
$image_margin = 'no' === get_field( 'constrain_image' ) ? 'my-auto' : '';
$align_image  = 'Top' === get_field( 'align_image' ) ? '' : 'align-items-center';

$align_text = 'Top' === get_field( 'align_text' ) ? '' : 'my-xl-auto';

// Support Gutenberg color picker.
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$section_id = $block['anchor'] ?? null;


$wm = get_field( 'show_watermark' ) ? 'block-watermark' : '';

$button_link = get_field( 'link' );
$link_url    = $button_link ? $button_link['url'] : '';
$link_image  = get_field( 'link_image' )[0] ?? null;

$block_id = $block['id'] ?? null;

$image_id  = get_field( 'image' );
$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
if ( ! $image_alt && $image_id ) {
	$image_alt = get_the_title( $image_id );
}
if ( $block_id ) {
	?>
<a id="<?= esc_attr( $block_id ); ?>"></a>
	<?php
}
?>
<section class="text-image <?= esc_attr( $wm . ' ' . $bg . ' ' . $fg ); ?>" id="<?= esc_attr( $section_id ); ?>">
	<div class="container py-5">
		<div class="row gx-5">
			<div class="<?= esc_attr( $text_width ); ?> <?= esc_attr( $text_order ); ?> <?= esc_attr( $align_text ); ?>" data-aos="<?= esc_attr( $text_aos ); ?>">
				<?php
				if ( get_field( 'title' ) ) {
					?>
				<h2 class="mb-5 fw-black"><?= wp_kses_post( get_field( 'title' ) ); ?></h2>
					<?php
				}
				?>
				<?= wp_kses_post( get_field( 'content' ) ); ?>
				<?php
				if ( $link_url ) {
					$target = $button_link['target'] ? $button_link['target'] : '_self';
					?>
					<a href="<?= esc_url( $link_url ); ?>" target="<?= esc_attr( $target ); ?>" class="btn btn--sipco-coral btn-arrow mt-3"><?= esc_html( $button_link['title'] ); ?></a>
					<?php
				}
				?>
			</div>
			<div class="<?= esc_attr( $image_width ); ?> <?= esc_attr( $image_order ); ?> d-flex <?= esc_attr( $align_image ); ?>" data-aos="<?= esc_attr( $image_aos ); ?>">
				<div class="w-100">
					<?php
					if ( $link_image && $link_url ) {
						?>
						<a href="<?= esc_url( $link_url ); ?>">
							<?=
							wp_get_attachment_image(
								$image_id,
								'full',
								false,
								array(
									'class' => esc_attr( $constrain ),
									'alt'   => esc_attr( $image_alt ),
								)
							);
							?>
						</a>
						<?php
					} else {
						echo wp_get_attachment_image(
							$image_id,
							'full',
							false,
							array(
								'class' => esc_attr( $constrain ),
								'alt'   => esc_attr( $image_alt ),
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>