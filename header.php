<?php
/**
 * The header for the theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

if ( session_status() === PHP_SESSION_NONE ) {
    session_start();
}



?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta
        charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">

	<link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/MonaSans_Condensed-Black.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/MonaSans_Condensed-Regular.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/MonaSans_Condensed-Light.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/hubot-sans-v5-latin-regular.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
	
    <?php
    if ( ! is_user_logged_in() ) {
        if ( get_field( 'ga_property', 'options' ) ) {
            ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async
                src="<?= esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . get_field( 'ga_property', 'options' ) ); ?>">
            </script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config',
                    '<?= esc_js( get_field( 'ga_property', 'options' ) ); ?>'
                );
            </script>
        	<?php
        }
        if ( get_field( 'gtm_property', 'options' ) ) {
            ?>
            <!-- Google Tag Manager -->
            <script>
                (function(w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({
                        'gtm.start': new Date().getTime(),
                        event: 'gtm.js'
                    });
                    var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s),
                        dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer',
                    '<?= esc_js( get_field( 'gtm_property', 'options' ) ); ?>'
                );
            </script>
            <!-- End Google Tag Manager -->
    		<?php
        }
    }
	if ( get_field( 'google_site_verification', 'options' ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( get_field( 'google_site_verification', 'options' ) ) . '" />';
	}
	if ( get_field( 'bing_site_verification', 'options' ) ) {
		echo '<meta name="msvalidate.01" content="' . esc_attr( get_field( 'bing_site_verification', 'options' ) ) . '" />';
	}
	wp_head();
	?>
</head>

<body <?php body_class( is_front_page() ? 'homepage' : '' ); ?>
    <?php understrap_body_attributes(); ?>>
    <?php
	do_action( 'wp_body_open' );
	if ( ! is_user_logged_in() ) {
    	if ( get_field( 'gtm_property', 'options' ) ) {
        	?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe
                    src="<?= esc_url( 'https://www.googletagmanager.com/ns.html?id=' . get_field( 'gtm_property', 'options' ) ); ?>"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
    		<?php
    	}
	}
	?>
    <header id="wrapperNavbar">
		<div class="container d-flex align-items-center justify-content-between">
			<a class="site-logo" href="/">
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/starbox-logo.webp' ); ?>" alt="Starbox" width="141" height="62" class="d-inline-block align-text-top">
			</a>
			<nav class="navbar">
				<button class="menu" id="menutoggle" type="button" data-bs-toggle="offcanvas"
					data-bs-target="#rightOffcanvas" aria-controls="rightOffcanvas">
					Menu <i class="fa-solid fa-bars"></i>
				</button>
				<div class="offcanvas offcanvas-end" tabindex="-1" id="rightOffcanvas"
					aria-labelledby="rightOffcanvasLabel">
					<div class="offcanvas-body">
						<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'primary_nav',
								'container_class' => 'container-xl w-100',
								'menu_class'      => 'navbar-nav justify-content-between gap-1',
								'fallback_cb'     => '',
								'menu_id'         => 'navbarr',
								'depth'           => 3,
								'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
							)
						);
						?>
					</div>
				</div>
			</nav>
		</div>
    </header>