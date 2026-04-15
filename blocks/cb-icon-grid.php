<?php
/**
 * Block template for CB Icon Grid.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$title      = get_field( 'title' );
$intro      = get_field( 'intro' );
$icons      = get_field( 'icons' );
$background = get_field( 'background' );

if ( is_array( $background ) ) {
	$background = $background['url'] ?? '';
	} elseif ( is_numeric( $background ) ) {
	$background = wp_get_attachment_image_url( $background, 'full' );
	} elseif ( is_string( $background ) ) {
	$background = $background;
} else {
	$background = '';
}

// Support Gutenberg color picker.
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$section_id = $block['anchor'] ?? null;
$extra      = $block['className'] ?? 'py-5';
$style_attr = $background ? 'background-image: url(' . esc_url( $background ) . ');' : '';

?>
<section class="cb-icon-grid <?= esc_attr( trim( $bg . ' ' . $fg . ' ' . $extra ) ); ?>" id="<?= esc_attr( $section_id ); ?>"<?php if ( $style_attr ) : ?> style="<?= esc_attr( $style_attr ); ?>"<?php endif; ?>>
	<div class="container cb-icon-grid__inner py-5">
		<?php if ( $title ) : ?>
			<h2 class="cb-icon-grid__title"><?= esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<div class="cb-icon-grid__intro"><?= wp_kses_post( $intro ); ?></div>
		<?php endif; ?>

		<?php if ( $icons ) : ?>
			<div class="cb-icon-grid__grid">
				<?php foreach ( $icons as $item ) : ?>
					<?php
					$icon_id    = $item['icon'] ?? null;
					$item_title = $item['title'] ?? '';
					$icon_alt   = $icon_id ? get_post_meta( $icon_id, '_wp_attachment_image_alt', true ) : '';

					if ( ! $icon_alt && $item_title ) {
						$icon_alt = $item_title;
					}
					?>
					<div class="cb-icon-grid__item">
						<?php
						if ( $icon_id ) {
							echo wp_get_attachment_image(
								$icon_id,
								'medium',
								false,
								array(
									'class' => 'cb-icon-grid__icon',
									'alt'   => $icon_alt,
								)
							);
						}
						?>
						<?php if ( $item_title ) : ?>
							<div class="cb-icon-grid__item-title"><?= esc_html( $item_title ); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
