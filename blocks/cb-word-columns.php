<?php
/**
 * Block template for CB Word Columns.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$words      = get_field( 'words' );
$section_id = $block['anchor'] ?? '';
$extra      = $block['className'] ?? '';

if ( empty( $words ) || ! is_array( $words ) ) {
	return;
}
?>
<section class="cb-word-columns <?= esc_attr( $extra ); ?>"<?php if ( $section_id ) : ?> id="<?= esc_attr( $section_id ); ?>"<?php endif; ?>>
	<div class="container">
		<div class="row g-4 g-lg-5 justify-content-center">
			<?php foreach ( $words as $item ) : ?>
				<?php $word = trim( (string) ( $item['word'] ?? '' ) ); ?>
				<?php if ( '' === $word ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<div class="col-md-6 col-lg-4">
					<div class="cb-word-columns__word"><?= esc_html( $word ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
