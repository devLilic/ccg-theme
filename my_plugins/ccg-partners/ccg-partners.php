<?php
/**
 * Plugin Name: Calatorii Cu Gust – Partners
 * Description: Modul pentru gestionarea partenerilor și sponsorilor (CPT, Meta, REST, Admin UI).
 * Version: 1.0.3
 * Author: Calatorii Cu Gust
 * Text Domain: ccg-partners
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_PARTNERS_FILE', __FILE__ );
define( 'CCG_PARTNERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_PARTNERS_URL',  plugin_dir_url( __FILE__ ) );
define( 'CCG_PARTNERS_VERSION', '1.0.3' );

/**
 * ============================================================
 * 1. LOAD TEXTDOMAIN – CORE FIX FOR WORDPRESS 6.7+
 * ============================================================
 *
 * Rulează cât mai devreme, dar după încărcarea pluginurilor.
 * Prioritatea 0 garantează evitarea warning-ului:
 *
 * “Translation loading for the ccg-partners domain was triggered too early”
 */
function ccg_partners_load_textdomain() {
    load_plugin_textdomain(
            'ccg-partners',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_partners_load_textdomain', 0 );


/**
 * ============================================================
 * 2. INCLUDE MODULE FILES – AFTER TEXTDOMAIN IS LOADED
 * ============================================================
 *
 * În WordPress 6.7+, fișierele nu trebuie să trateze __()
 * înainte ca domeniul de limbă să fie încărcat.
 */
function ccg_partners_include_files() {

    require_once CCG_PARTNERS_PATH . 'helpers/partners-utils.php';
    require_once CCG_PARTNERS_PATH . 'post-types/partners-cpt.php';
    require_once CCG_PARTNERS_PATH . 'post-types/partners-admin-columns.php';
    require_once CCG_PARTNERS_PATH . 'meta/partners-meta.php';
    require_once CCG_PARTNERS_PATH . 'api/partners-rest.php';
}
add_action( 'plugins_loaded', 'ccg_partners_include_files', 1 );


/**
 * ============================================================
 * 3. CHECK ccg-core DEPENDENCY
 * ============================================================
 */
function ccg_partners_check_core() {
    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>ccg-partners</strong>: Modulul <code>ccg-core</code> trebuie să fie activ.</p></div>';
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
 * Se execută DUPĂ ce:
 * – textdomain este încărcat
 * – fișierele modulului sunt incluse
 * – ccg-core este verificat
 */
function ccg_partners_bootstrap() {

    if ( ! ccg_partners_check_core() ) {
        return;
    }

    // CPT
    if ( function_exists( 'ccg_partners_register_cpt' ) ) {
        add_action( 'init', 'ccg_partners_register_cpt', 2 );
    }

    // Metabox
    if ( function_exists( 'ccg_partners_register_metabox' ) ) {
        add_action( 'init', 'ccg_partners_register_metabox', 2);
    }

    // Admin Columns
    if ( function_exists( 'ccg_partners_register_admin_columns' ) ) {
        add_action( 'admin_init', 'ccg_partners_register_admin_columns' );
    }

    // REST API
    if ( function_exists( 'ccg_partners_register_rest' ) ) {
        add_action( 'rest_api_init', 'ccg_partners_register_rest' );
    }

    // Admin Assets
    add_action( 'admin_enqueue_scripts', 'ccg_partners_enqueue_admin_assets' );
}
add_action( 'plugins_loaded', 'ccg_partners_bootstrap', 20 );


/**
 * ============================================================
 * 5. LOAD ADMIN ASSETS ONLY ON PARTNER SCREENS
 * ============================================================
 */
function ccg_partners_enqueue_admin_assets( $hook ) {

    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'partners' ) {
        return;
    }

    wp_enqueue_style(
            'ccg-partners-admin',
            CCG_PARTNERS_URL . 'assets/css/admin-partners.css',
            [],
            CCG_PARTNERS_VERSION
    );

    wp_enqueue_script(
            'ccg-partners-admin',
            CCG_PARTNERS_URL . 'assets/js/admin-partners.js',
            [ 'jquery' ],
            CCG_PARTNERS_VERSION,
            true
    );
}
