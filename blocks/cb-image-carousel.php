<?php
/**
 * Block template for CB Image Carousel.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$images     = get_field( 'images' );
$block_id   = $block['id'] ?? '';
$section_id = $block['anchor'] ?? $block_id;
$extra      = $block['className'] ?? '';

if ( empty( $images ) || ! is_array( $images ) ) {
	return;
}
?>
<section id="<?= esc_attr( $section_id ); ?>" class="cb-image-carousel <?= esc_attr( $extra ); ?>">
	<div class="container py-5">
		<h2 class="text-center fw-black fs-900"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="text-center mb-4 w-constrained mx-auto" style="--width:80ch"><?= wp_kses_post( get_field( 'intro' ) ); ?></div>
		<div class="cb-image-carousel__marquee">
			<div class="cb-image-carousel__track">
				<div class="cb-image-carousel__slides">
					<?php foreach ( $images as $image_id ) : ?>
						<?php
						$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
						if ( ! $alt ) {
							$alt = get_the_title( $image_id );
						}
						?>
						<div class="cb-image-carousel__slide">
							<div class="cb-image-carousel__media">
								<?= wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'cb-image-carousel__image', 'alt' => $alt ) ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	if (typeof window.gsap === 'undefined') return;

	function whenImagesReady(scope, callback) {
		var images = scope.querySelectorAll('img');
		if (!images.length) {
			callback();
			return;
		}

		var pending = images.length;
		function done() {
			pending--;
			if (pending <= 0) callback();
		}

		images.forEach(function(img) {
			if (img.complete && img.naturalWidth > 0) {
				done();
				return;
			}
			img.addEventListener('load', done, { once: true });
			img.addEventListener('error', done, { once: true });
		});
	}

	function measureSlidesWidth(wrapperEl) {
		var slides = wrapperEl.querySelectorAll('.cb-image-carousel__slide');
		if (!slides.length) return 0;

		var computed = window.getComputedStyle(wrapperEl);
		var gap = parseFloat(computed.columnGap || computed.gap || '0') || 0;
		var total = 0;

		slides.forEach(function(slide, index) {
			total += slide.getBoundingClientRect().width;
			if (index < slides.length - 1) total += gap;
		});

		return total;
	}

	document.querySelectorAll('.cb-image-carousel').forEach(function(block) {
		var container = block.querySelector('.cb-image-carousel__marquee');
		var wrapper = block.querySelector('.cb-image-carousel__track .cb-image-carousel__slides');
		if (!container || !wrapper) return;

		gsap.killTweensOf(wrapper);
		gsap.set(wrapper, { x: 0 });

		var originalSlides = Array.from(wrapper.children).map(function(node) {
			return node.cloneNode(true);
		});
		if (!originalSlides.length) return;

		whenImagesReady(wrapper, function() {
			wrapper.innerHTML = '';
			originalSlides.forEach(function(slide) {
				wrapper.appendChild(slide.cloneNode(true));
			});

			var singleSetWidth = measureSlidesWidth(wrapper);
			if (!singleSetWidth) return;

			var minBaseSets = Math.max(2, Math.ceil(container.clientWidth / singleSetWidth) + 1);
			for (var i = 1; i < minBaseSets; i++) {
				originalSlides.forEach(function(slide) {
					wrapper.appendChild(slide.cloneNode(true));
				});
			}

			var baseSetMarkup = wrapper.innerHTML;
			var baseWidth = measureSlidesWidth(wrapper);
			if (!baseWidth) return;

			wrapper.innerHTML += baseSetMarkup;

			var pxPerSecond = 90;
			var duration = baseWidth / pxPerSecond;
			gsap.to(wrapper, {
				x: -baseWidth,
				duration: duration,
				ease: 'none',
				repeat: -1,
			});
		});
	});
});
</script>
		<?php
	},
	20
);
