<?php
/**
 * Block template for CB Testimonial.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="cb-testimonial">
	<div class="container py-5">
		<h2 class="fs-950 fw-black has-white-color mb-5">Testimonial</h2>
		<div class="row g-5 justify-content-center align-items-center">
			<div class="col-md-2">
				<?= wp_get_attachment_image( get_field( 'image' ), 'full'); ?>
			</div>
			<div class="col-md-8">
				<div class="cb-testimonial__quote"><?= wp_kses_post( get_field( 'quote' ) ); ?></div>
				<div class="cb-testimonial__attribution"><?= esc_html( get_field( 'attribution' ) ); ?></div>
			</div>
		</div>
	</div>
</section>