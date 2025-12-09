<?php
/**
 * Plugin Name: Calatorii Cu Gust – Taxonomy Manager
 * Description: Manager centralizat pentru taxonomii (regiuni, zone turistice etc.) și asocierea lor cu post type-uri.
 * Version: 1.0.1
 * Author: Calatorii Cu Gust
 * Text Domain: ccg-taxonomy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_TAXMGR_FILE', __FILE__ );
define( 'CCG_TAXMGR_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_TAXMGR_URL',  plugin_dir_url( __FILE__ ) );
define( 'CCG_TAXMGR_VERSION', '1.0.1' );

/**
 * 1. TEXTDOMAIN – WordPress 6.7+ safe
 */
function ccg_taxmgr_load_textdomain() {
    load_plugin_textdomain(
        'ccg-taxonomy',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ccg_taxmgr_load_textdomain', 0 );

/**
 * 2. INCLUDE FILES – după textdomain
 */
function ccg_taxmgr_include_files() {

    require_once CCG_TAXMGR_PATH . 'helpers/utils.php';
    require_once CCG_TAXMGR_PATH . 'post-types/ccg-taxonomy-cpt.php';
    require_once CCG_TAXMGR_PATH . 'admin/taxonomy-meta.php';
    require_once CCG_TAXMGR_PATH . 'register/taxonomy-register.php';
    require_once CCG_TAXMGR_PATH . 'admin/taxonomy-seed-array.php';

}
add_action( 'plugins_loaded', 'ccg_taxmgr_include_files', 1 );

/**
 * 3. VERIFICĂ ccg-core
 */
function ccg_taxmgr_check_core() {
    if ( ! function_exists( 'ccg_core_register_taxonomy' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>ccg-taxonomy</strong>: Pluginul <code>ccg-core</code> trebuie să fie activ.</p></div>';
        } );
        return false;
    }
    return true;
}

/**
 * 4. BOOTSTRAP – AICI E ORDINEA IMPORTANTĂ
 *
 *  - rulăm după ce TOATE pluginurile sunt încărcate (plugins_loaded 20)
 *  - legăm ccg_taxmgr_register_cpt pe init priority 5
 *  - legăm ccg_taxmgr_register_dynamic_taxonomies pe init priority 30
 *  - admin_menu rulează DUPĂ init, deci taxonomiile deja există
 */
function ccg_taxmgr_bootstrap() {

    if ( ! ccg_taxmgr_check_core() ) {
        return;
    }

    // 4.1 CPT configurator – cât mai devreme în init
    add_action( 'init', 'ccg_taxmgr_register_cpt', 5 );

    // 4.2 Metabox & salvare
    add_action( 'add_meta_boxes', 'ccg_taxmgr_register_metabox' );
    add_action( 'save_post_ccg_taxonomy', 'ccg_taxmgr_save_taxonomy_meta' );

    // 4.3 Taxonomii dinamice – DUPĂ ce toate CPT-urile sunt înregistrate
    //    (ccg-places, ccg-events etc. își înregistrează CPT-urile la init 10)
    add_action( 'after_setup_theme', 'ccg_taxmgr_register_dynamic_taxonomies', 99 );

    // 4.4 Meniu admin – rulează după init, taxonomiile există deja
    add_action( 'admin_menu', 'ccg_taxmgr_admin_menu', 60 );
}
add_action( 'plugins_loaded', 'ccg_taxmgr_bootstrap', 20 );

/**
 * 5. MENIU ADMIN – Taxonomy Manager + submeniuri
 *
 *  - Top-level: Taxonomy Manager → listă ccg_taxonomy (config)
 *  - Submeniu principal: Config taxonomii
 *  - Submeniuri suplimentare: câte unul pentru fiecare taxonomie EXISTENTĂ
 *    (verificăm cu taxonomy_exists ca să nu mai apară "Taxonomia nu este validă")
 */
function ccg_taxmgr_admin_menu() {

    // Top-level: Taxonomy Manager
    add_menu_page(
        __( 'Taxonomy Manager', 'ccg-taxonomy' ),
        __( 'Taxonomy Manager', 'ccg-taxonomy' ),
        'manage_options',
        'edit.php?post_type=ccg_taxonomy',
        '',
        'dashicons-tag',
        25
    );

    // Submeniu "Config taxonomii"
    add_submenu_page(
        'edit.php?post_type=ccg_taxonomy',
        __( 'Config taxonomii', 'ccg-taxonomy' ),
        __( 'Config taxonomii', 'ccg-taxonomy' ),
        'manage_options',
        'edit.php?post_type=ccg_taxonomy'
    );

    // Submeniuri dinamice pentru fiecare taxonomie
    $tax_posts = get_posts( [
        'post_type'      => 'ccg_taxonomy',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'no_found_rows'  => true,
    ] );

    if ( ! $tax_posts ) {
        return;
    }

    foreach ( $tax_posts as $tax_post ) {

        $slug = get_post_meta( $tax_post->ID, '_ccgtax_slug', true );
        if ( ! $slug ) {
            $slug = sanitize_title( $tax_post->post_title );
        }

        // dacă taxonomia NU este înregistrată, NU creăm submeniul
        if ( ! taxonomy_exists( $slug ) ) {
            continue;
        }

        // aflăm un post_type asociat, pentru URL
        $pts_csv = get_post_meta( $tax_post->ID, '_ccgtax_post_types', true );
        $pts     = $pts_csv ? array_filter( array_map( 'trim', explode( ',', $pts_csv ) ) ) : [];
        $primary_pt = ! empty( $pts ) ? $pts[0] : 'post';

        $menu_title = $tax_post->post_title;

        add_submenu_page(
            'edit.php?post_type=ccg_taxonomy',
            sprintf( __( 'Termeni: %s', 'ccg-taxonomy' ), $menu_title ),
            $menu_title,
            'manage_options',
            'edit-tags.php?taxonomy=' . urlencode( $slug )
        );
    }
}
add_action('init', function() {
    error_log('CPTs la init: ' . print_r(array_keys(get_post_types()), true));
}, 9);

add_action('init', function() {
    error_log('CPTs la init(20): ' . print_r(array_keys(get_post_types()), true));
}, 20);
