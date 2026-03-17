<?php
/**
 * Block template for CB Full Hero.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$button = get_field( 'button' );
?>
<section class="full-hero">
	<div class="container d-flex flex-column align-items-center justify-content-center text-center">
		<div class="full-hero__pre-title">
			<?= esc_html( get_field( 'pre_title' ) ); ?>
		</div>
		<h1 class="full-hero__title">
			<?= esc_html( get_field( 'title' ) ); ?>
		</h1>
		<div class="text-center" style="z-index: 1;">
			<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary"><?= esc_html( $button['title'] ); ?></a>
		</div>
	</div>
	<img class="full-hero__cube" src="<?= esc_url( get_stylesheet_directory_uri() . '/img/Energy-Cube@1045x887.webp' ); ?>" alt="">
</section>

<?php
if ( ! function_exists( 'cb_full_hero_footer_animation' ) ) {
	/**
	 * Print GSAP animation for full hero title in footer.
	 */
	function cb_full_hero_footer_animation() {
		?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

	const heroTitles = document.querySelectorAll('.full-hero__title');
	if (!heroTitles.length) return;

	gsap.registerPlugin(ScrollTrigger);

	if (window.cbLenis) {
		window.cbLenis.on('scroll', ScrollTrigger.update);
	}

	heroTitles.forEach(function (heroTitle) {
		const heroSection = heroTitle.closest('.full-hero');
		if (!heroSection) return;

		gsap.to(heroTitle, {
			scale: 1.6,
			autoAlpha: 0,
			transformOrigin: '50% 50%',
			ease: 'none',
			scrollTrigger: {
				id: 'full-hero-title-' + Math.random().toString(36).slice(2),
				trigger: heroSection,
				start: 'top top',
				end: 'bottom top',
				scrub: true,
				invalidateOnRefresh: true
			}
		});
	});

	requestAnimationFrame(function () {
		ScrollTrigger.refresh();
	});

	window.addEventListener('load', function () {
		ScrollTrigger.refresh();
	}, { once: true });

	window.addEventListener('pageshow', function () {
		ScrollTrigger.refresh();
	});
});
</script>
		<?php
	}
}
add_action( 'wp_footer', 'cb_full_hero_footer_animation', 100 );
?>