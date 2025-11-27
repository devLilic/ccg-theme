<?php
/**
 * Plugin Name: Calatorii Cu Gust – Places
 * Description: Modulul principal pentru gestionarea locațiilor turistice: CPT, taxonomii, meta, hartă Leaflet, REST API.
 * Version: 1.0.3
 * Author: Calatorii Cu Gust
 * Text Domain: ccg-places
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_PLACES_FILE', __FILE__ );
define( 'CCG_PLACES_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_PLACES_URL',  plugin_dir_url( __FILE__ ) );
define( 'CCG_PLACES_VERSION', '1.0.3' );

/**
 * ============================================================
 * 1. LOAD TEXTDOMAIN (FIX PENTRU WORDPRESS 6.7+)
 * ============================================================
 *
 * Trebuie încărcat înainte să apară orice apel __() / _e().
 * Prioritate 0 ≈ cel mai devreme moment sigur.
 */
function ccg_places_load_textdomain() {
    load_plugin_textdomain(
            'ccg-places',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_places_load_textdomain', 0 );


/**
 * ============================================================
 * 2. INCLUDE MODULE FILES – DUPĂ textdomain
 * ============================================================
 *
 * Orice fișier care conține __() trebuie încărcat după textdomain.
 */
function ccg_places_include_files() {

    require_once CCG_PLACES_PATH . 'helpers/places-utils.php';

    // CPT & Taxonomii
    require_once CCG_PLACES_PATH . 'post-types/places-cpt.php';
    require_once CCG_PLACES_PATH . 'post-types/places-taxonomies.php';

    // Meta fields & metabox
    require_once CCG_PLACES_PATH . 'meta/place-options.php';

    require_once CCG_PLACES_PATH . 'meta/places-meta.php';


    // Admin columns
    require_once CCG_PLACES_PATH . 'post-types/places-admin-columns.php';

    // REST API
    require_once CCG_PLACES_PATH . 'api/places-rest.php';
}
add_action( 'plugins_loaded', 'ccg_places_include_files', 1 );


/**
 * ============================================================
 * 3. VERIFICĂ DEPENDENȚA ccg-core
 * ============================================================
 */
function ccg_places_check_core() {
    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>ccg-places</strong>: Modulul <code>ccg-core</code> trebuie să fie activ pentru a funcționa.</p></div>';
        });
        return false;
    }
    return true;
}


/**
 * ============================================================
 * 4. BOOTSTRAP MODULE
 * ============================================================
 *
 * Aici se înregistrează efectiv:
 * – CPT
 * – Taxonomii
 * – Meta + Metabox
 * – Admin columns
 * – REST API
 * – Asset-uri pentru Leaflet și galerie în admin
 */
function ccg_places_bootstrap() {

    if ( ! ccg_places_check_core() ) {
        return;
    }

    // CPT & Taxonomii
    if ( function_exists( 'ccg_places_register_cpt' ) ) {
        add_action( 'init', 'ccg_places_register_cpt', 2 );
    }

    if ( function_exists( 'ccg_places_register_taxonomies' ) ) {
        add_action( 'init', 'ccg_places_register_taxonomies', 2 );
    }

    // Metabox
    if ( function_exists( 'ccg_places_register_metabox' ) ) {
        add_action( 'init', 'ccg_places_register_metabox', 2 );
    }

    // Admin Columns
    if ( function_exists( 'ccg_places_register_admin_columns' ) ) {
        add_action( 'admin_init', 'ccg_places_register_admin_columns' );
    }

    // REST API
    if ( function_exists( 'ccg_places_register_rest' ) ) {
        add_action( 'rest_api_init', 'ccg_places_register_rest' );
    }

    // Admin assets (includes Leaflet)
    add_action( 'admin_enqueue_scripts', 'ccg_places_enqueue_admin_assets' );
}
add_action( 'plugins_loaded', 'ccg_places_bootstrap', 20 );


/**
 * ============================================================
 * 5. ADMIN ASSETS — Leaflet, galerie, CSS custom
 * ============================================================
 */
function ccg_places_enqueue_admin_assets() {

    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'place' ) {
        return;
    }

    // Leaflet CSS
    wp_enqueue_style(
            'leaflet-css',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            [],
            '1.9.4'
    );

    // Leaflet JS
    wp_enqueue_script(
            'leaflet-js',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            [],
            '1.9.4',
            true
    );

    // Plugin CSS
    wp_enqueue_style(
            'ccg-places-admin',
            CCG_PLACES_URL . 'assets/css/admin-places.css',
            [],
            CCG_PLACES_VERSION
    );

    // Admin logic (gallery + map)
    wp_enqueue_script(
            'ccg-places-admin',
            CCG_PLACES_URL . 'assets/js/admin-places.js',
            [ 'jquery', 'leaflet-js' ],
            CCG_PLACES_VERSION,
            true
    );
}

//require_once __DIR__ . '/includes/ccg-places-taxonomy-seed.php';
