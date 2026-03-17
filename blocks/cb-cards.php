<?php
/**
 * Block template for CB Cards.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

$button = get_field( 'button' );

?>
<section class="cb-cards bg-grad-green">
	<div class="container">
		<h2 class="text-center has-white-color">
			<?= esc_html( get_field( 'title' ) ); ?>
		</h2>
		<div class="row g-4 mb-5">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				?>
				<div class="col-md-6 col-xl-4">
					<?php
					if ( get_sub_field( 'link' ) ) {
						$l = get_sub_field( 'link' );
						?>
					<a class="cb-card" href="<?= esc_url( $l['url'] ); ?>">
						<?php
					} else {
						?>					
					<div class="cb-card">
						<?php
					}
					?>
						<h3 class="cb-card__title has-white-color"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
						<p class="cb-card__description has-white-color"><?= esc_html( get_sub_field( 'description' ) ); ?></p>
						<?php
						if ( get_sub_field( 'link' ) ) {
							?>
					</a>
							<?php
						} else {
							?>
					</div>
							<?php
						}
						?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="text-center">
			<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary"><?= esc_html( $button['title'] ); ?></a>
		</div>
	</div>
</section>