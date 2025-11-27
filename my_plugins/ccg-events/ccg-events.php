<?php
/**
 * Plugin Name: Calatorii Cu Gust – Events
 * Description: Modul pentru gestionarea evenimentelor turistice: CPT, taxonomii, meta, REST API.
 * Version: 1.0.0
 * Author: Calatorii Cu Gust
 * Text Domain: ccg-events
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_EVENTS_FILE', __FILE__ );
define( 'CCG_EVENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_EVENTS_URL',  plugin_dir_url( __FILE__ ) );
define( 'CCG_EVENTS_VERSION', '1.0.0' );

/**
 * 1. Textdomain – încărcat devreme (WordPress 6.7+ safe)
 */
function ccg_events_load_textdomain() {
    load_plugin_textdomain(
        'ccg-events',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_events_load_textdomain', 0 );

/**
 * 2. Include fișiere – după textdomain
 */
function ccg_events_include_files() {

    require_once CCG_EVENTS_PATH . 'helpers/events-utils.php';

    require_once CCG_EVENTS_PATH . 'post-types/events-cpt.php';
    require_once CCG_EVENTS_PATH . 'post-types/events-admin-columns.php';

    require_once CCG_EVENTS_PATH . 'taxonomies/events-taxonomies.php';

    require_once CCG_EVENTS_PATH . 'meta/events-meta.php';

    require_once CCG_EVENTS_PATH . 'api/events-rest.php';

//    require_once CCG_EVENTS_PATH . 'includes/ccg-events-seed.php';
}
add_action( 'plugins_loaded', 'ccg_events_include_files', 1 );

/**
 * 3. Verifică ccg-core
 */
function ccg_events_check_core() {
    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>ccg-events</strong>: Modulul <code>ccg-core</code> trebuie să fie activ pentru a funcționa.</p></div>';
        } );
        return false;
    }
    return true;
}

/**
 * 4. Bootstrap modul
 */
function ccg_events_bootstrap() {

    if ( ! ccg_events_check_core() ) {
        return;
    }

    // CPT
    if ( function_exists( 'ccg_events_register_cpt' ) ) {
        add_action( 'init', 'ccg_events_register_cpt', 2 );
    }

    // Taxonomii proprii
    if ( function_exists( 'ccg_events_register_taxonomies' ) ) {
        add_action( 'init', 'ccg_events_register_taxonomies', 2 );
    }

    // Meta & metabox
    if ( function_exists( 'ccg_events_register_metabox' ) ) {
        add_action( 'init', 'ccg_events_register_metabox', 2 );
    }

    // Admin columns
    if ( function_exists( 'ccg_events_register_admin_columns' ) ) {
        add_action( 'admin_init', 'ccg_events_register_admin_columns' );
    }

    // REST
    if ( function_exists( 'ccg_events_register_rest' ) ) {
        add_action( 'rest_api_init', 'ccg_events_register_rest' );
    }

    // Assets admin
    add_action( 'admin_enqueue_scripts', 'ccg_events_enqueue_admin_assets' );

    // Reatașăm taxonomiile globale region/tourism_zone la event
    add_action( 'init', 'ccg_events_attach_shared_taxonomies', 20 );
}
add_action( 'plugins_loaded', 'ccg_events_bootstrap', 20 );

/**
 * 5. Atașăm taxonomiile comune (region/tourism_zone) la CPT event
 */
function ccg_events_attach_shared_taxonomies() {
    // Dacă taxonomia există (definită în ccg-places), o atașăm și la event
    if ( taxonomy_exists( 'place_region' ) ) {
        register_taxonomy_for_object_type( 'place_region', 'event' );
    }
    if ( taxonomy_exists( 'tourism_zone' ) ) {
        register_taxonomy_for_object_type( 'tourism_zone', 'event' );
    }
}

/**
 * 6. Admin assets
 */
function ccg_events_enqueue_admin_assets() {
    $screen = get_current_screen();
    if ( ! $screen || 'event' !== $screen->post_type ) {
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

    wp_enqueue_style(
        'ccg-events-admin',
        CCG_EVENTS_URL . 'assets/css/admin-events.css',
        [],
        CCG_EVENTS_VERSION
    );

    wp_enqueue_script(
        'ccg-events-admin',
        CCG_EVENTS_URL . 'assets/js/admin-events.js',
        [ 'jquery', 'leaflet-js' ],
        CCG_EVENTS_VERSION,
        true
    );

    // Custom map code
    wp_enqueue_script('ccg-events-map', CCG_EVENTS_URL . 'assets/js/admin-events-map.js',
        ['jquery','leaflet-js'], CCG_EVENTS_VERSION, true);

    wp_enqueue_style('ccg-events-map', CCG_EVENTS_URL . 'assets/css/admin-events-map.css');

    wp_enqueue_media();
}
