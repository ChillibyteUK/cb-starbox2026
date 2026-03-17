<?php
/**
 * Block template for CB Team Grid.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="team-grid">
	<div class="container py-5">
		<div class="text-center fs-600 w-md-75 w-lg-50 mx-auto pb-5"><?= wp_kses_post( get_field( 'intro' ) ); ?></div>

		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'team_member',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);
		if ( $q->have_posts() ) {
			?>
		<div class="team-grid__grid mt-4">
			<?php
			while ( $q->have_posts() ) {
				$q->the_post();
				?>
			<a href="<?= esc_url( get_permalink() ); ?>" class="team-grid__member">
				<div class="gradient"></div>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'team-grid__member-image' ) ); ?>
				<div class="team-grid__info">
					<div class="team-grid__member-name mt-2">
						<div class="text-swap">
							<span class="text-swap__mask">
								<span class="text-swap__inner">
									<span class="text-swap__text"><?= esc_html( get_the_title() ); ?></span>
									<span class="text-swap__text" aria-hidden="true"><?= esc_html( get_the_title() ); ?></span>
								</span>
							</span>
						</div>	
					</div>
					<div class="team-grid__member-role"><?= esc_html( get_field( 'role', get_the_ID() ) ); ?></div>
				</div>
				<div class="colour-grad"></div>
			</a>
				<?php
			}
			?>
		</div>
			<?php
			wp_reset_postdata();
		}
		?>

	</div>
</section>