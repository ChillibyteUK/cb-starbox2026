<?php
/**
 * Block template for CB Showcase Scroller.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$panel_one    = get_field( 'panel_one_bg' );
$panel_one_bg = $panel_one ? $panel_one['url'] : '';

$panel_four    = get_field( 'panel_four_bg' );
$panel_four_bg = $panel_four ? $panel_four['url'] : '';
$panel_two_title = 'Trusted by our clients';
$showcase_scroller_id = wp_unique_id( 'showcase-scroller-' );

?>
<section class="showcase-scroller" id="<?= esc_attr( $showcase_scroller_id ); ?>">
	<div class="panel-one" style="background-image:url(<?= esc_url( $panel_one_bg ) ?>);"></div>
	<div class="panel-two">
		<?php
		$panel_two_images = get_field( 'panel_two_images' );
		$panel_two_images = is_array( $panel_two_images ) ? array_values( $panel_two_images ) : array();
		$image_id_from_value = static function ( $value ) {
			if ( is_numeric( $value ) ) {
				return (int) $value;
			}

			if ( is_array( $value ) && isset( $value['ID'] ) ) {
				return (int) $value['ID'];
			}

			if ( is_object( $value ) && isset( $value->ID ) ) {
				return (int) $value->ID;
			}

			return 0;
		};

		// Index 0 is intentionally reserved for later use.
		$panel_two_slots = array(
			array(
				'class'     => 'slot--left-one',
				'tilt'      => '-8deg',
				'top_id'    => isset( $panel_two_images[1] ) ? $image_id_from_value( $panel_two_images[1] ) : 0,
				'bottom_id' => isset( $panel_two_images[4] ) ? $image_id_from_value( $panel_two_images[4] ) : 0,
			),
			array(
				'class'     => 'slot--left-two',
				'tilt'      => '8deg',
				'top_id'    => isset( $panel_two_images[2] ) ? $image_id_from_value( $panel_two_images[2] ) : 0,
				'bottom_id' => isset( $panel_two_images[5] ) ? $image_id_from_value( $panel_two_images[5] ) : 0,
			),
			array(
				'class'     => 'slot--bottom-centre',
				'tilt'      => '-6deg',
				'top_id'    => isset( $panel_two_images[3] ) ? $image_id_from_value( $panel_two_images[3] ) : 0,
				'bottom_id' => isset( $panel_two_images[6] ) ? $image_id_from_value( $panel_two_images[6] ) : 0,
			),
		);

		echo wp_get_attachment_image( $panel_two_images[0], 'large', false, array( 'class' => 'showcase-scroller__img-two') );
		?>
		<div class="showcase-scroller__stage">
			<div class="showcase-scroller__media-wrap" aria-hidden="true">
				<div class="showcase-scroller__media-stage">
					<?php foreach ( $panel_two_slots as $slot ) : ?>
						<?php if ( empty( $slot['top_id'] ) && empty( $slot['bottom_id'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<div class="showcase-scroller__media-slot <?= esc_attr( $slot['class'] ); ?>">
							<?php if ( ! empty( $slot['top_id'] ) ) : ?>
								<div class="showcase-scroller__media-item showcase-scroller__media-item--top">
									<div class="showcase-scroller__media-tilt" style="--slot-inline-tilt: <?= esc_attr( $slot['tilt'] ); ?>;">
										<?= wp_get_attachment_image( $slot['top_id'], 'large' ); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $slot['bottom_id'] ) ) : ?>
								<div class="showcase-scroller__media-item showcase-scroller__media-item--bottom">
									<div class="showcase-scroller__media-tilt" style="--slot-inline-tilt: <?= esc_attr( $slot['tilt'] ); ?>;">
										<?= wp_get_attachment_image( $slot['bottom_id'], 'large' ); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="showcase-scroller__title-wrap">
				<div class="showcase-scroller__title">
					<span class="showcase-scroller__title-base"><?= esc_html( $panel_two_title ); ?></span>
					<span class="showcase-scroller__title-fill" aria-hidden="true"><?= esc_html( $panel_two_title ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-three">
		<?php
		$panel_three_images = get_field( 'panel_three_images' );
		$panel_three_images = is_array( $panel_three_images ) ? array_values( $panel_three_images ) : array();

		$panel_three_slots = array(
			array(
				'final_x'     => 30,
				'final_y'     => 30,
				'from_x'      => '-45vw',
				'from_y'      => '-45vh',
				'start_scale' => '0.92',
				'z_index'     => 1,
			),
			array(
				'final_x'     => 65,
				'final_y'     => 35,
				'from_x'      => '45vw',
				'from_y'      => '-45vh',
				'start_scale' => '0.92',
				'z_index'     => 4,
			),
			array(
				'final_x'     => 33,
				'final_y'     => 75,
				'from_x'      => '-45vw',
				'from_y'      => '45vh',
				'start_scale' => '0.92',
				'z_index'     => 3,
			),
			array(
				'final_x'     => 63,
				'final_y'     => 80,
				'from_x'      => '45vw',
				'from_y'      => '45vh',
				'start_scale' => '0.92',
				'z_index'     => 2,
			),
			array(
				'final_x'     => 50,
				'final_y'     => 50,
				'from_x'      => '0vw',
				'from_y'      => '0vh',
				'start_scale' => '0.3',
				'z_index'     => 0,
			),
		);
		?>
		<div class="showcase-scroller__panel-three-stage" aria-hidden="true">
			<?php foreach ( $panel_three_slots as $index => $slot ) : ?>
				<?php
				$image_id = isset( $panel_three_images[ $index ] ) ? $image_id_from_value( $panel_three_images[ $index ] ) : 0;
				if ( empty( $image_id ) ) {
					continue;
				}
				$slot_style = sprintf(
					'--p3-final-x:%1$d;--p3-final-y:%2$d;--p3-from-x:%3$s;--p3-from-y:%4$s;--p3-start-scale:%5$s;--p3-z-index:%6$d;',
					(int) $slot['final_x'],
					(int) $slot['final_y'],
					esc_attr( $slot['from_x'] ),
					esc_attr( $slot['from_y'] ),
					esc_attr( $slot['start_scale'] ),
					(int) $slot['z_index'],
				);
				?>
				<div class="showcase-scroller__panel-three-card" style="<?= esc_attr( $slot_style ); ?>">
					<?= wp_get_attachment_image( $image_id, 'large' ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="panel-four" style="background-image:url(<?= esc_url( $panel_four_bg ) ?>);">
		panel four
	</div>
</section>
<script>
	(function() {
		const section = document.getElementById( <?= wp_json_encode( $showcase_scroller_id ); ?> );
		if (!section) {
			return;
		}

		const panelTwo = section.querySelector('.panel-two');
		const panelThree = section.querySelector('.panel-three');
		const title = section.querySelector('.showcase-scroller__title');
		const slots = Array.from(section.querySelectorAll('.showcase-scroller__media-slot'));
		if (!panelTwo || !title) {
			return;
		}

		let ticking = false;

		const update = () => {
			const panelRect = panelTwo.getBoundingClientRect();
			const titleRect = title.getBoundingClientRect();

			// The true white/black divider is the panel midpoint in viewport space.
			const dividerY = panelRect.top + (panelRect.height / 2);

			// Keep title color split locked to the divider line.
			const splitOffset = Math.max(0, Math.min(titleRect.height, dividerY - titleRect.top));
			title.style.setProperty('--title-fill-offset', `${splitOffset}px`);

			// Reveal second image set directly from the divider crossing each fixed slot.
			slots.forEach((slot) => {
				const bottomItem = slot.querySelector('.showcase-scroller__media-item--bottom');
				if (!bottomItem) return;

				const slotRect = slot.getBoundingClientRect();
				const slotH = Math.max(1, slotRect.height);
				// Divider moves upward through the viewport as panel-two scrolls.
				// Keep image hidden while divider is below the slot, then reveal from
				// bottom->top as the divider crosses the slot bounds.
				const reveal = Math.max(0, Math.min(1, (slotRect.bottom - dividerY) / slotH));
				slot.style.setProperty('--slot-reveal', reveal.toString());
			});

			if (panelThree) {
				const panelThreeRect = panelThree.getBoundingClientRect();
				const viewportH = window.innerHeight || document.documentElement.clientHeight;
				const stickyTravel = Math.max(1, panelThreeRect.height - viewportH);
				const holdDistance = viewportH * 0.35; // Keep cards parked for ~35vh.
				const animDistance = Math.max(1, stickyTravel - holdDistance);
				const scrolled = Math.max(0, Math.min(stickyTravel, -panelThreeRect.top));
				const clamped = Math.max(0, Math.min(1, scrolled / animDistance));
				const eased = 1 - Math.pow(1 - clamped, 3);
				panelThree.style.setProperty('--panel-three-progress', eased.toString());
			}

			ticking = false;
		};

		const requestUpdate = () => {
			if (ticking) return;
			ticking = true;
			window.requestAnimationFrame(update);
		};

		// Initialise reveal state.
		slots.forEach((slot) => {
			slot.style.setProperty('--slot-reveal', '0');
		});
		if (panelThree) {
			panelThree.style.setProperty('--panel-three-progress', '0');
		}
		requestUpdate();
		window.addEventListener('scroll', requestUpdate, { passive: true });
		window.addEventListener('resize', requestUpdate, { passive: true });
		window.addEventListener('load', requestUpdate, { passive: true });
	})();
</script>