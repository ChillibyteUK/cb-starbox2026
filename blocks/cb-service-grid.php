<?php
/**
 * Block template for CB Service Grid.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$title    = get_field( 'title' );
$intro    = get_field( 'intro' );
$services = get_field( 'services' );

// Support Gutenberg color picker.
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$section_id = $block['anchor'] ?? null;
$extra      = $block['className'] ?? '';

?>
<section class="cb-service-grid <?= esc_attr( trim( $bg . ' ' . $fg . ' ' . $extra ) ); ?>" id="<?= esc_attr( $section_id ); ?>">
	<div class="container cb-service-grid__inner">
		<?php if ( $title ) : ?>
			<h2 class="cb-service-grid__title" data-aos="fade"><?= esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<div class="cb-service-grid__intro" data-aos="fade"><?= wp_kses_post( $intro ); ?></div>
		<?php endif; ?>

		<?php if ( $services ) : ?>
			<div class="row g-4 cb-service-grid__grid">
				<?php
				$delay = 0;
				foreach ( $services as $service ) {
					$image_id    = $service['image'] ?? null;
					$item_title  = $service['title'] ?? '';
					$description = $service['description'] ?? '';
					$link        = $service['link'] ?? null;
					$tag         = ! empty( $link['url'] ) ? 'a' : 'div';
					$image_alt   = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
					?>
					<div class="col-md-6 col-xl-4" data-aos="fade" data-aos-delay="<?= esc_attr( $delay ); ?>">
						<<?= esc_attr( $tag ); ?> class="cb-service-grid__card"
						<?php
						if ( 'a' === $tag ) {
							?>
							href="<?= esc_url( $link['url'] ); ?>" target="<?= esc_attr( ! empty( $link['target'] ) ? $link['target'] : '_self' ); ?>"
							<?php
						}
						?>
						>
							<?php
							?>
							<div class="cb-service-grid__head">
							<?php
							if ( $image_id ) {
								echo wp_get_attachment_image(
									$image_id,
									'medium',
									false,
									array(
										'class' => 'cb-service-grid__image',
										'alt'   => esc_attr( $image_alt ),
									)
								);
							}
							if ( $item_title ) {
								?>
								<h3 class="cb-service-grid__card-title"><?= esc_html( $item_title ); ?></h3>
								<?php
							}
							?>
							</div>
							<?php
							if ( $description ) {
								?>
								<p class="cb-service-grid__card-description"><?= esc_html( $description ); ?></p>
								<?php
							}
							?>
						</<?= esc_attr( $tag ); ?>>
					</div>
					<?php
					$delay += 100;
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
