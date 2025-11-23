<?php
/**
 * Plugin Name: Calatorii Cu Gust - Partners
 * Description: Gestionarea Partenerilor & Sponosorilor (CPT partners, meta, REST, admin UI).
 * Version: 1.0.0
 * Author: CCG Dev
 * Text Domain: ccg-partners
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_PARTNERS_VERSION', '1.0.0' );
define( 'CCG_PARTNERS_FILE', __FILE__ );
define( 'CCG_PARTNERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_PARTNERS_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Asigurăm că ccg-core este activ.
 */
function ccg_partners_check_core_dependency() {
    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        add_action( 'admin_notices', function () {
            ?>
            <div class="notice notice-error">
                <p><strong>ccg-partners</strong>: Pluginul <code>ccg-core</code> trebuie să fie activ înainte de acest modul.</p>
            </div>
            <?php
        } );
    }
}
add_action( 'plugins_loaded', 'ccg_partners_check_core_dependency', 5 );

/**
 * Load text domain.
 */
function ccg_partners_load_textdomain() {
    load_plugin_textdomain(
        'ccg-partners',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_partners_load_textdomain' );

/**
 * Include fișierele modulului.
 */
require_once CCG_PARTNERS_PATH . 'helpers/partners-utils.php';
require_once CCG_PARTNERS_PATH . 'post-types/partners-cpt.php';
require_once CCG_PARTNERS_PATH . 'post-types/partners-admin-columns.php';
require_once CCG_PARTNERS_PATH . 'meta/partners-meta.php';
require_once CCG_PARTNERS_PATH . 'api/partners-rest.php';

/**
 * Bootstrap modul: înregistrăm CPT, meta, REST etc.
 */
function ccg_partners_bootstrap() {

    if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
        return;
    }

    // CPT
    ccg_partners_register_cpt();

    // Metabox / meta
    ccg_partners_register_metabox();

    // Admin columns
    ccg_partners_register_admin_columns();

    // REST API
    ccg_partners_register_rest();

    // Assets specifice admin pentru acest modul
    add_action( 'admin_enqueue_scripts', 'ccg_partners_enqueue_admin_assets' );
}
add_action( 'plugins_loaded', 'ccg_partners_bootstrap', 20 );

/**
 * Assets admin pentru modul (aplicate doar pe ecranul CPT-ului).
 */
function ccg_partners_enqueue_admin_assets( $hook ) {
    $screen = get_current_screen();
    if ( ! $screen || 'partners' !== $screen->post_type ) {
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
