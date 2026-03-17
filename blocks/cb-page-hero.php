<?php
/**
 * Block template for CB Page Hero.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$colour = get_field( 'colour' ) ? get_field( 'colour' ) : 'pink';

?>
<section class="page-hero page-hero--<?= esc_attr( strtolower( $colour ) ); ?> d-flex align-items-center">
	<div class="container py-5">
		<div class="row">
			<div class="col-md-7">
				<h1><?= esc_html( get_field( 'title' ) ); ?></h1>
			</div>
			<div class="col-md-4 offset-md-1 my-auto">
				<div class="page-hero__content">
					<?= wp_kses_post( get_field( 'content' ) ); ?>
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
(function () {
	var hero = document.querySelector('.page-hero');
	if (!hero || typeof gsap === 'undefined') {
		return;
	}

	var h1 = hero.querySelector('h1');
	var content = hero.querySelector('.page-hero__content');

	if (h1) {
		gsap.to(h1, { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' });
	}

	if (content) {
		gsap.to(content, { opacity: 1, duration: 0.8, delay: 0.3, ease: 'power2.out' });
	}
})();
</script>
		<?php
	},
	20
);