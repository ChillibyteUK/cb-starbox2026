<?php
/**
 * Style guide endpoint renderer.
 *
 * @package cb-starbox2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'hsl_to_rgb_hex' ) ) {
    /**
     * Converts an HSL color value to HEX and RGB string values.
     *
     * @param string $hsl HSL value in the format "H S% L%".
     * @return array
     */
    function hsl_to_rgb_hex( $hsl ) {
        preg_match( '/(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)%\s+(\d+(?:\.\d+)?)%/', $hsl, $parts );
        if ( count( $parts ) !== 4 ) {
            return array( '', '' );
        }

        $h = $parts[1];
        $s = $parts[2] / 100;
        $l = $parts[3] / 100;

        $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
        $x = $c * ( 1 - abs( fmod( $h / 60, 2 ) - 1 ) );
        $m = $l - $c / 2;

        if ( $h < 60 ) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ( $h < 120 ) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ( $h < 180 ) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ( $h < 240 ) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ( $h < 300 ) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = round( ( $r + $m ) * 255 );
        $g = round( ( $g + $m ) * 255 );
        $b = round( ( $b + $m ) * 255 );

        return array( sprintf( '#%02x%02x%02x', $r, $g, $b ), "rgb($r, $g, $b)" );
    }
}

if ( ! function_exists( 'get_template_description' ) ) {
    /**
     * Extracts a plain-language description from a file doc block.
     *
     * @param string $template_path Full path to a template file.
     * @return string
     */
    function get_template_description( $template_path ) {
        if ( ! file_exists( $template_path ) ) {
            return '';
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $contents = file_get_contents( $template_path );
        if ( ! preg_match( '/\/\*\*(.*?)\*\//s', $contents, $docblock ) ) {
            return '';
        }

        $doc_content = trim( $docblock[1] );
        $lines       = preg_split( '/\R/', $doc_content );
        $description = array();

        foreach ( $lines as $line ) {
            $line = preg_replace( '/^\s*\*\s?/', '', $line );
            if ( strpos( $line, '@' ) === 0 ) {
                break;
            }
            if ( '' !== $line ) {
                $description[] = $line;
            }
        }

        return implode( ' ', $description );
    }
}

/**
 * Resolve the best available token source file.
 *
 * @return string Empty string when no token source is found.
 */
function cb_styleguide_get_token_file_path() {
    $candidates = array(
        get_stylesheet_directory() . '/src/sass/theme/_tokens.scss',
        get_stylesheet_directory() . '/src/sass/theme/_props.scss',
        get_stylesheet_directory() . '/css/child-theme.css',
    );

    foreach ( $candidates as $candidate ) {
        if ( file_exists( $candidate ) ) {
            return $candidate;
        }
    }

    return '';
}

/**
 * Render CSS variable references parsed from the token file.
 *
 * @return void
 */
function cb_styleguide_render_css_variables_tab() {
    $file_path = cb_styleguide_get_token_file_path();
    if ( '' === $file_path ) {
        echo '<div class="alert alert-warning">No token source file found.</div>';
        return;
    }

    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
    $file_contents = file_get_contents( $file_path );
    if ( false === $file_contents ) {
        echo '<div class="alert alert-danger">Failed to read token source file: ' . esc_html( $file_path ) . '</div>';
        return;
    }

    preg_match_all( '/--(.*?)\s*:\s*(.*?)\s*;/m', $file_contents, $matches, PREG_SET_ORDER, 0 );

    $colours  = array();
    $fsizes   = array();
    $fweights = array();

    foreach ( $matches as $match ) {
        $variable_name  = trim( $match[1] );
        $variable_value = trim( $match[2] );

        if ( preg_match( '/^col/', $variable_name ) ) {
            $colours[ $variable_name ] = $variable_value;
        }

        if ( preg_match( '/^fs/', $variable_name ) ) {
            $fsizes[ $variable_name ] = $variable_value;
        }

        if ( preg_match( '/^fw/', $variable_name ) ) {
            $fweights[ $variable_name ] = $variable_value;
        }
    }

    echo '<p class="text-muted">Token source: <code>' . esc_html( str_replace( get_stylesheet_directory(), '', $file_path ) ) . '</code></p>';
    echo '<h2 class="cb-sg-heading">Colours</h2>';
    foreach ( $colours as $name => $value ) {
        echo wp_kses_post( cb_styleguide_colour_row( $name, $value ) );
    }

    echo '<h2 class="cb-sg-heading">Font Sizes</h2>';
    foreach ( array_reverse( $fsizes ) as $name => $value ) {
        echo wp_kses_post( cb_styleguide_type_row( $name, $value ) );
    }

    echo '<h2 class="cb-sg-heading">Font Weights</h2>';
    foreach ( array_reverse( $fweights ) as $name => $value ) {
        echo wp_kses_post( cb_styleguide_weight_row( $name, $value ) );
    }
}

/**
 * Render one style-guide row for a colour token.
 *
 * @param string $name Token name.
 * @param string $value Token value.
 * @return string
 */
function cb_styleguide_colour_row( $name, $value ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    ob_start();
    ?>
    <div class="cb-sg-output">
        <div class="cb-sg-title">--<?= esc_html( $name ); ?></div>
        <div class="cb-sg-value">
            <div class="cb-sg-single" style="background-color:var(--<?= esc_attr( $name ); ?>)"></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render one style-guide row for a font-size token.
 *
 * @param string $name Token name.
 * @param string $value Token value.
 * @return string
 */
function cb_styleguide_type_row( $name, $value ) {
    ob_start();
    ?>
    <div class="cb-sg-output">
        <div class="cb-sg-title">--<?= esc_html( $name ); ?></div>
        <div class="cb-sg-value" style="font-size:<?= esc_attr( $value ); ?>">Lorem ipsum dolor sit amet consectetur.</div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render one style-guide row for a font-weight token.
 *
 * @param string $name Token name.
 * @param string $value Token value.
 * @return string
 */
function cb_styleguide_weight_row( $name, $value ) {
    ob_start();
    ?>
    <div class="cb-sg-output">
        <div class="cb-sg-title">--<?= esc_html( $name ); ?></div>
        <div class="cb-sg-value" style="font-size:var(--fs-400);font-weight:<?= esc_attr( $value ); ?>">Lorem ipsum dolor sit amet consectetur.</div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Include a style-guide reference part safely.
 *
 * @param string $slug Reference part slug.
 * @return void
 */
function cb_styleguide_render_reference_part( $slug ) {
    $allowed_slugs = array( 'typography', 'colours', 'buttons', 'forms', 'grid', 'blocks' );
    if ( ! in_array( $slug, $allowed_slugs, true ) ) {
        echo '<div class="alert alert-danger">Invalid style guide section requested.</div>';
        return;
    }

    if ( 'blocks' === $slug && ! function_exists( 'acf_get_block_types' ) ) {
        echo '<div class="alert alert-warning">ACF is required to render block previews.</div>';
        return;
    }

    $path = get_stylesheet_directory() . '/reference-parts/' . $slug . '.php';
    if ( ! file_exists( $path ) ) {
        echo '<div class="alert alert-warning">Missing reference file: <code>' . esc_html( $path ) . '</code></div>';
        return;
    }

    ob_start();
    try {
        include $path;
        echo ob_get_clean();
    } catch ( Throwable $exception ) {
        ob_end_clean();
        echo '<div class="alert alert-danger">Failed to render <code>' . esc_html( $slug ) . '</code> tab: ' . esc_html( $exception->getMessage() ) . '</div>';
    }
}

$tabs = array(
    'css-variables' => array(
        'label' => 'CSS Variables',
        'renderer' => 'cb_styleguide_render_css_variables_tab',
    ),
    'typography' => array(
        'label' => 'Typography',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'typography' );
        },
    ),
    'colours' => array(
        'label' => 'Colours',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'colours' );
        },
    ),
    'buttons' => array(
        'label' => 'Buttons',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'buttons' );
        },
    ),
    'forms' => array(
        'label' => 'Forms',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'forms' );
        },
    ),
    'grid' => array(
        'label' => 'Grid',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'grid' );
        },
    ),
    'blocks' => array(
        'label' => 'Blocks',
        'renderer' => function () {
            cb_styleguide_render_reference_part( 'blocks' );
        },
    ),
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Guide</title>
    <link rel="stylesheet" href="<?= esc_url( get_stylesheet_directory_uri() . '/css/child-theme.css' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>">
    <style>
        body {
            background: #f4f7fa;
        }

        .cb-sg-shell {
            padding: 2.5rem 0;
        }

        .cb-sg-title-row {
            margin-bottom: 1.5rem;
        }

        .cb-sg-heading {
            border-bottom: 1px solid hsl(0 0% 0% / 0.2);
            margin: 1.75rem 0 1rem;
            padding-bottom: 0.35rem;
        }

        .cb-sg-tabs-wrap {
            background: #fff;
            border: 1px solid #d9e0e7;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 8px 32px rgb(16 23 36 / 0.08);
        }

        .cb-sg-tabs {
            border-bottom: 1px solid #e7edf3;
            padding: 0.5rem 0.75rem;
            gap: 0.35rem;
            background: linear-gradient(180deg, #f9fbfd, #f4f7fa);
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .cb-sg-tabs .nav-link {
            color: #394b5d;
            border: 1px solid transparent;
            border-radius: 0.5rem;
            white-space: nowrap;
            font-size: 0.95rem;
        }

        .cb-sg-tabs .nav-link.active {
            background: #101724;
            color: #fff;
            border-color: #101724;
        }

        .cb-sg-tab-content {
            padding: 1.25rem;
        }

        .cb-sg-tab-pane {
            display: none;
        }

        .cb-sg-tab-pane.active {
            display: block;
        }

        .cb-sg-output {
            display: grid;
            grid-template-columns: 200px auto;
            gap: 0.75rem;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e2e8ef;
        }

        .cb-sg-title {
            align-self: center;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.925rem;
        }

        .cb-sg-value {
            width: 100%;
            min-height: 50px;
            align-self: center;
        }

        .cb-sg-single {
            width: 100%;
            min-height: 50px;
            border-radius: 0.4rem;
            border: 1px solid rgb(0 0 0 / 0.15);
        }

        @media (max-width: 767px) {
            .cb-sg-tab-content {
                padding: 1rem;
            }

            .cb-sg-output {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="cb-sg-shell">
        <div class="container-xl">
            <div class="cb-sg-title-row">
                <h1 class="mb-2">Theme Style Guide</h1>
                <p class="text-muted mb-0">Tabbed reference for tokens, typography, colours, buttons, forms, grid, and ACF blocks.</p>
            </div>

            <div class="cb-sg-tabs-wrap">
                <ul class="nav nav-tabs cb-sg-tabs" id="cbStyleGuideTabs" role="tablist">
                    <?php
                    $is_first_tab = true;
                    foreach ( $tabs as $slug => $config ) :
                        ?>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link<?= $is_first_tab ? ' active' : ''; ?>"
                                id="tab-<?= esc_attr( $slug ); ?>"
                                type="button"
                                role="tab"
                                data-tab="<?= esc_attr( $slug ); ?>"
                                aria-controls="panel-<?= esc_attr( $slug ); ?>"
                                aria-selected="<?= $is_first_tab ? 'true' : 'false'; ?>"
                            >
                                <?= esc_html( $config['label'] ); ?>
                            </button>
                        </li>
                        <?php
                        $is_first_tab = false;
                    endforeach;
                    ?>
                </ul>

                <div class="cb-sg-tab-content">
                    <?php
                    $is_first_panel = true;
                    foreach ( $tabs as $slug => $config ) :
                        ?>
                        <section
                            class="cb-sg-tab-pane<?= $is_first_panel ? ' active' : ''; ?>"
                            id="panel-<?= esc_attr( $slug ); ?>"
                            role="tabpanel"
                            aria-labelledby="tab-<?= esc_attr( $slug ); ?>"
                        >
                            <?php call_user_func( $config['renderer'] ); ?>
                        </section>
                        <?php
                        $is_first_panel = false;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var tabButtons = document.querySelectorAll('#cbStyleGuideTabs [data-tab]');
            var tabPanels = document.querySelectorAll('.cb-sg-tab-pane');

            function activateTab(slug) {
                tabButtons.forEach(function (button) {
                    var isActive = button.getAttribute('data-tab') === slug;
                    button.classList.toggle('active', isActive);
                    button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                tabPanels.forEach(function (panel) {
                    panel.classList.toggle('active', panel.id === 'panel-' + slug);
                });
            }

            tabButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    activateTab(button.getAttribute('data-tab'));
                });
            });
        })();
    </script>
</body>

</html>