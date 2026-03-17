<?php
/**
 * Block template for CB Contact.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="contact">
	<div class="container">
		<div class="row g-">
			<div class="col-md-6">
				<h1>Enquire</h1>
				<div class="contact__content">
					See how our expert team can help you.

					<?php
					if ( get_field( 'contact_email', 'option' ) ) {
						echo '<div>' . do_shortcode( '[contact_email]' ) . '</div>';
					}
					if ( get_field( 'contact_phone', 'option' ) ) {
						echo '<div>' . do_shortcode( '[contact_phone]' ) . '</div>';
					}
					?>
				</div>
			</div>
			<div class="col-md-6">
				<?= do_shortcode( '[gravityform id="1" title="false" ajax="true"]'); ?>
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
	var hero = document.querySelector('.contact');
	if (!hero || typeof gsap === 'undefined') {
		return;
	}

	var h1 = hero.querySelector('h1');
	var content = hero.querySelector('.contact__content');

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