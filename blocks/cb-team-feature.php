<?php
/**
 * Block template for CB Team Feature.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="team-feature">
	<div class="team-feature__marquee mb-5" aria-hidden="true">
		<div class="team-feature__track">
			<div class="team-feature__slides">
				<div class="team-feature__slide"><span class="team-feature__word">THE TEAM</span></div>
			</div>
		</div>
	</div>
	<div class="container py-5">
		<div class="text-center fs-600 w-md-75 w-lg-50 mx-auto pb-5"><?= wp_kses_post( get_field( 'intro' ) ); ?></div>

		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'team_member',
				'posts_per_page' => 4,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);
		if ( $q->have_posts() ) {
			?>
		<div class="team-feature__members_grid mt-4">
			<?php
			while ( $q->have_posts() ) {
				$q->the_post();
				?>
			<a href="<?= esc_url( get_permalink() ); ?>" class="team-feature__member">
				<div class="gradient"></div>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'team-feature__member-image' ) ); ?>
				<div class="team-feature__info">
					<div class="team-feature__member-name mt-2">
						<div class="text-swap">
							<span class="text-swap__mask">
								<span class="text-swap__inner">
									<span class="text-swap__text"><?= esc_html( get_the_title() ); ?></span>
									<span class="text-swap__text" aria-hidden="true"><?= esc_html( get_the_title() ); ?></span>
								</span>
							</span>
						</div>	
					</div>
					<div class="team-feature__member-role"><?= esc_html( get_field( 'role', get_the_ID() ) ); ?></div>
				</div>
				<div class="colour-grad"></div>
			</a>
				<?php
			}
			?>
			<a href="/team/" class="team-feature__member team-feature__member--all">
				<div class="gradient"></div>
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/Starbox-grad.webp' ); ?>" class="team-feature__member-image" alt="">
				<div class="team-feature__info">
					<div class="team-feature__member-name mt-2 w-100 d-flex justify-content-between align-items-center">
						<div class="text-swap">
							<span class="text-swap__mask">
								<span class="text-swap__inner">
									<span class="text-swap__text">View All</span>
									<span class="text-swap__text" aria-hidden="true">View All</span>
								</span>
							</span>
						</div>
						<div class="button-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 17" fill="none"><path d="M12.175 9.5H0L0 7.5H12.175L6.575 1.9L8 0.5L16 8.5L8 16.5L6.575 15.1L12.175 9.5Z" fill="currentColor"></path></svg>
						</div>
					</div>
				</div>
				<div class="colour-grad"></div>
			</a>
		</div>
			<?php
			wp_reset_postdata();
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
	if (typeof window.gsap === 'undefined') return;

	function measureSlidesWidth(wrapperEl) {
		var slides = wrapperEl.querySelectorAll('.team-feature__slide');
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

	document.querySelectorAll('.team-feature').forEach(function(block) {
		var container = block.querySelector('.team-feature__marquee');
		var wrapper = block.querySelector('.team-feature__track .team-feature__slides');
		if (!container || !wrapper) return;

		gsap.killTweensOf(wrapper);
		gsap.set(wrapper, { x: 0 });

		var originalSlides = Array.from(wrapper.children).map(function(node) {
			return node.cloneNode(true);
		});
		if (!originalSlides.length) return;

		wrapper.innerHTML = '';
		originalSlides.forEach(function(slide) {
			wrapper.appendChild(slide.cloneNode(true));
		});

		var singleSetWidth = measureSlidesWidth(wrapper);
		if (!singleSetWidth) return;

		var minBaseSets = Math.max(1, Math.ceil(container.clientWidth / singleSetWidth) + 1);
		for (var i = 1; i < minBaseSets; i++) {
			originalSlides.forEach(function(slide) {
				wrapper.appendChild(slide.cloneNode(true));
			});
		}

		var baseSetMarkup = wrapper.innerHTML;
		var baseWidth = measureSlidesWidth(wrapper);
		if (!baseWidth) return;

		wrapper.innerHTML += baseSetMarkup;

		var pxPerSecond = 80;
		var duration = baseWidth / pxPerSecond;
		gsap.to(wrapper, {
			x: -baseWidth,
			duration: duration,
			ease: 'none',
			repeat: -1,
		});
	});
});
</script>
		<?php
	}
);