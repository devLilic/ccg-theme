<?php
/**
 * Plugin Name: Calatorii Cu Gust - Places
 * Description: Gestionarea locațiilor turistice (CPT place, taxonomii, meta, REST, hartă Leaflet).
 * Version: 1.1.0
 * Author: CCG Dev
 * Text Domain: ccg-places
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_PLACES_VERSION', '1.1.0' );
define( 'CCG_PLACES_FILE', __FILE__ );
define( 'CCG_PLACES_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_PLACES_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Asigurăm că ccg-core este activ.
 */
function ccg_places_check_core_dependency() {
    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        add_action( 'admin_notices', function () {
            ?>
            <div class="notice notice-error">
                <p><strong>ccg-places</strong>: Pluginul <code>ccg-core</code> trebuie să fie activ înainte de acest modul.</p>
            </div>
            <?php
        } );
    }
}
add_action( 'plugins_loaded', 'ccg_places_check_core_dependency', 5 );

/**
 * Textdomain.
 */
function ccg_places_load_textdomain() {
    load_plugin_textdomain(
            'ccg-places',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_places_load_textdomain' );

/**
 * Include fișierele modulului.
 */
require_once CCG_PLACES_PATH . 'helpers/places-utils.php';
require_once CCG_PLACES_PATH . 'post-types/places-taxonomies.php';
require_once CCG_PLACES_PATH . 'post-types/places-cpt.php';
require_once CCG_PLACES_PATH . 'post-types/places-admin-columns.php';
require_once CCG_PLACES_PATH . 'meta/places-meta.php';
require_once CCG_PLACES_PATH . 'api/places-rest.php';

/**
 * Bootstrap modul.
 */
function ccg_places_bootstrap() {

    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        return;
    }

    // Taxonomii
    ccg_places_register_taxonomies();

    // CPT
    ccg_places_register_cpt();

    // Metabox-uri
    ccg_places_register_metabox();

    // Admin columns
    ccg_places_register_admin_columns();

    // REST
    ccg_places_register_rest();

    // Assets admin
    add_action( 'admin_enqueue_scripts', 'ccg_places_enqueue_admin_assets' );
}
add_action( 'plugins_loaded', 'ccg_places_bootstrap', 20 );

/**
 * Assets admin – doar pe ecranele CPT-ului place.
 */
function ccg_places_enqueue_admin_assets() {
    $screen = get_current_screen();
    if ( ! $screen || 'place' !== $screen->post_type ) {
        return;
    }

    // Leaflet CSS & JS (CDN)
    wp_enqueue_style(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            [],
            '1.9.4'
    );

    wp_enqueue_script(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            [],
            '1.9.4',
            true
    );

    // CSS specific modulului
    wp_enqueue_style(
            'ccg-places-admin',
            CCG_PLACES_URL . 'assets/css/admin-places.css',
            [ 'leaflet' ],
            CCG_PLACES_VERSION
    );

    // JS specific modulului
    wp_enqueue_script(
            'ccg-places-admin',
            CCG_PLACES_URL . 'assets/js/admin-places.js',
            [ 'jquery', 'leaflet' ],
            CCG_PLACES_VERSION,
            true
    );
}

//require_once __DIR__ . '/includes/ccg-places-taxonomy-seed.php';
