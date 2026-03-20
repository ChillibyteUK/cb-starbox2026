<?php
/**
 * Footer template for the Starbox 2026 theme.
 *
 * This file contains the footer section of the theme, including navigation menus,
 * office addresses, and colophon information.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="footer-top"></div>

<footer class="footer pt-5">
	<div class="footer__marquee mb-5" aria-hidden="true">
		<div class="footer__track">
			<div class="footer__slides">
				<div class="footer__slide"><span class="footer__word">get in touch • Starbox — Your business is our business •</span></div>
			</div>
		</div>
	</div>
    <div class="container px-4 px-md-5">
        <div class="row pb-4 g-4">
			<div class="col-12 col-md-6">
				<div class="footer__business mb-3">Your business<br>is our business</div>
				<a href="/contact/" class="btn btn-dark">Get in touch</a>
			</div>
			<div class="col-6 col-sm-4 col-md-2">
				<div class="footer-title">Links</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_links',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
            <div class="col-6 col-sm-4 col-md-2">
				<div class="footer-title">More</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_more',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
            <div class="col-12 col-sm-4 col-md-2">
				<div class="footer-title">Follow us</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_social',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="footer__logo-wrapper">
			<div class="footer__logo">Starbox</div>
		</div>
	</div>
	<div class="container px-4 px-md-5 py-4 footer__colophon d-flex gap-2 flex-wrap justify-content-between align-items-center">
		<div>
		&copy; <?= esc_html( gmdate( 'Y' ) ); ?> StarBox is a trading name of <a href="https://www.carpenterbox.com/" target="_blank">Carpenter Box Limited</a><br>
		<?= do_shortcode( '[contact_phone]' ); ?> / <?= do_shortcode( '[contact_email]' ); ?>
		</div>
		<div>
			<a href="/privacy-policy/">Privacy Policy</a> / <a href="/cookies/">Cookies</a>
		</div>
	</div>
</footer>

<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	if (typeof window.gsap === 'undefined') return;

	function measureSlidesWidth(wrapperEl) {
		var slides = wrapperEl.querySelectorAll('.footer__slide');
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

	// Pad body so page content can scroll up to reveal fixed footer
	var footerEl = document.querySelector('.footer');
	function setFooterPadding() {
		if (footerEl) document.body.style.paddingBottom = footerEl.offsetHeight + 'px';
	}
	setFooterPadding();
	new ResizeObserver(setFooterPadding).observe(document.body);

	/// Logo text: fit width to container, crop at 75% height
	var logo = document.querySelector('.footer__logo');
	var logoWrapper = document.querySelector('.footer__logo-wrapper');
	if (logo && logoWrapper) {
		function fitLogoText() {
			logo.style.fontSize = '100px';
			// Measure intrinsic text width by making it shrink-to-content
			logo.style.display = 'inline-block';
			var textWidth = logo.getBoundingClientRect().width;
			logo.style.display = 'block';
			if (!textWidth) return;
			var px = logoWrapper.clientWidth / textWidth * 100;
			logo.style.fontSize = px + 'px';
			logoWrapper.style.height = (px * 0.75) + 'px';
		}
		fitLogoText();
		new ResizeObserver(fitLogoText).observe(logoWrapper);
	}

	document.querySelectorAll('.footer').forEach(function(block) {
		var container = block.querySelector('.footer__marquee');
		var wrapper = block.querySelector('.footer__track .footer__slides');
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

wp_footer(); ?>
</body>

</html>