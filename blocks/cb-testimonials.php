<?php
/**
 * Block template for CB Testimonials.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="testimonials py-5">
	<div class="container">
		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'testimonial',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( $q->have_posts() ) {
			echo '<div class="testimonials__swiper swiper my-4">';
			echo '<button class="testimonials__button testimonials__button-prev" type="button" aria-label="Previous testimonial"></button>';

			echo '<div class="swiper-wrapper pt-2 pb-3">';
			while ( $q->have_posts() ) {
				$q->the_post();
				$testimonial = get_the_content();
				$author      = get_the_title();
				$role_co     = get_field( 'role', get_the_ID() );
				?>
				<div class="swiper-slide">
					<div class="testimonial mb-4">
						<div class="testimonial__text"><?= wp_kses_post( $testimonial ); ?></div>
						<div class="testimonial__meta">
							<div class="testimonial__image">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'thumbnail' );
								} else {
									echo '<div class="testimonial__placeholder"><span class="dashicons dashicons-person"></span></div>';
								}
								?>
							</div>
							<div>
								<div class="testimonial__author"><?= esc_html( $author ); ?></div>
								<div class="testimonial__role"><?= esc_html( $role_co ); ?></div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			echo '</div>';
			echo '<button class="testimonials__button testimonials__button-next" type="button" aria-label="Next testimonial"></button>';
			echo '</div>';
		}
		?>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var swiperEl = document.querySelector('.testimonials__swiper.swiper');
	if (!swiperEl) {
		return;
	}

	function animateActiveSlide(swiperInstance) {
		if (typeof gsap === 'undefined') {
			return;
		}

		swiperInstance.slides.forEach(function(slide) {
			var slideParts = slide.querySelectorAll('.testimonial__text, .testimonial__meta');
			if (slideParts.length) {
				gsap.set(slideParts, { autoAlpha: 0, y: 20 });
			}
		});

		var activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
		if (!activeSlide) {
			return;
		}

		var activeParts = activeSlide.querySelectorAll('.testimonial__text, .testimonial__meta');
		if (!activeParts.length) {
			return;
		}

		gsap.killTweensOf(activeParts);
		gsap.to(activeParts, {
			autoAlpha: 1,
			y: 0,
			duration: 0.65,
			stagger: 0.14,
			ease: 'power2.out'
		});
	}

	var swiper = new Swiper(swiperEl, {
		loop: true,
		autoplay: {
			delay: 5000,
			disableOnInteraction: true,
		},
		effect: 'fade',
		fadeEffect: {
			crossFade: true,
		},
		slidesPerView: 1,
		spaceBetween: 24,
		navigation: {
			nextEl: '.testimonials__button-next',
			prevEl: '.testimonials__button-prev',
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		on: {
			init: function() {
				animateActiveSlide(this);
			},
			slideChangeTransitionStart: function() {
				animateActiveSlide(this);
			},
		},
	});
});
</script>
		<?php
	}
);