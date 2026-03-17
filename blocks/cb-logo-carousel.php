<?php
/**
 * Block template for CB Logo Carousel.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

// Block ID.
$block_id = $block['id'] ?? '';

?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-logo-carousel">
	<div class="id-container cb-logo-carousel__marquee">
		<div class="cb-logo-carousel__track">
			<div class="cb-logo-carousel__slides">
				<?php
				$logos = get_field( 'logos' );
				if ( $logos ) {
					foreach ( $logos as $logo ) {
						?>
				<div class="cb-logo-carousel__slide">
						<?=
						wp_get_attachment_image(
							$logo,
							'full',
							false,
							array(
								'class' => 'cb-logo-carousel__logo img-fluid',
								'alt'   => get_post_meta( $logo, '_wp_attachment_image_alt', true ),
							)
						);
						?>
				</div>
						<?php
					}
				}
				?>
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
		var slides = wrapperEl.querySelectorAll('.cb-logo-carousel__slide');
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

	document.querySelectorAll('.cb-logo-carousel').forEach(function(block) {
		var container = block.querySelector('.cb-logo-carousel__marquee');
		var wrapper = block.querySelector('.cb-logo-carousel__track .cb-logo-carousel__slides');
		if (!container || !wrapper) return;

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

			var minBaseSets = Math.max(1, Math.ceil(container.clientWidth / singleSetWidth));
			for (var i = 1; i < minBaseSets; i++) {
				originalSlides.forEach(function(slide) {
					wrapper.appendChild(slide.cloneNode(true));
				});
			}

			var baseSetMarkup = wrapper.innerHTML;
			var baseWidth = measureSlidesWidth(wrapper);
			if (!baseWidth) return;

			wrapper.innerHTML += baseSetMarkup;

			var pxPerSecond = 80; // Adjust for desired speed
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
	}
);