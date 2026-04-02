<?php
/**
 * Block template for CB Careers.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	function () {
		?>
<link rel="stylesheet" href="https://dywrfp5ctng3l.cloudfront.net/external-job-postings-table/index.css">
<script type="module" crossorigin src="https://dywrfp5ctng3l.cloudfront.net/external-job-postings-table/index.js"></script>
		<?php
	}
);
?>
<section class="careers">
	<div class="container">
		<div data-pinpoint-subdomain="sumer" data-pinpoint-custom-structure-name="Brand" data-pinpoint-division-filter-ids="6741" data-pinpoint-structure-custom-group-one-filter-ids="7325" data-pinpoint-department-filter-disabled="true" data-pinpoint-primary-theme-color="#455C51" data-pinpoint-secondary-theme-color="#F4F4F4" data-pinpoint-highlight-theme-color="#999999" class="pinpoint-external-jobs-table-widget"></div>
	</div>
</section>