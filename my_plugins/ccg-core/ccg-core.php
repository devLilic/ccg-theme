<?php
/**
 * Plugin Name: Calatorii Cu Gust - Core
 * Description: Framework intern pentru platforma calatoriicugust.com (utilitare OOP, helper-e, REST, meta, roluri, asset-uri).
 * Version: 1.0.0
 * Author: CCG Dev
 * Text Domain: ccg-core
 */

use CCG\Core\Meta\MetaBoxManager;
use CCG\Core\Plugin;
use CCG\Core\PostTypes\PostTypeRegistrar;
use CCG\Core\Taxonomies\TaxonomyRegistrar;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_CORE_VERSION', '1.0.0' );
define( 'CCG_CORE_FILE', __FILE__ );
define( 'CCG_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_CORE_URL',  plugin_dir_url( __FILE__ ) );
define( 'CCG_CORE_SRC',  CCG_CORE_PATH . 'src/' );

/**
 * PSR-4 like autoloader pentru namespace-ul CCG\Core\*
 */
require_once CCG_CORE_SRC . 'Autoloader.php';

CCG\Core\Autoloader::register();

/**
 * Helper global pentru a obține instanța principală de plugin.
 *
 * @return Plugin
 */
function ccg_core() {
    return Plugin::instance();
}

// Bootstrapping
add_action( 'plugins_loaded', function() {
    ccg_core()->init();
} );

/**
 * Funcții helper expuse pentru MODULE
 * ===================================
 * Modulele pot apela aceste funcții în loc să acceseze direct clasele.
 */

if ( ! function_exists( 'ccg_core_register_post_type' ) ) {
    /**
     * Înregistrare CPT prin registrul din core.
     *
     * @param string $post_type
     * @param array  $args
     * @param string $module Slugul modulului (ex: 'ccg-partners')
     */
    function ccg_core_register_post_type( $post_type, array $args, $module = '' ) {
        PostTypeRegistrar::queue_post_type( $post_type, $args, $module );
    }
}

if ( ! function_exists( 'ccg_core_register_taxonomy' ) ) {
    function ccg_core_register_taxonomy( $taxonomy, $object_type, array $args, $module = '' ) {
        TaxonomyRegistrar::queue_taxonomy( $taxonomy, $object_type, $args, $module );
    }
}

if ( ! function_exists( 'ccg_core_register_metabox' ) ) {
    function ccg_core_register_metabox( \CCG\Core\Meta\MetaBox $metabox ) {
        MetaBoxManager::register_metabox( $metabox );
    }
}

if ( ! function_exists( 'ccg_core_register_rest_routes' ) ) {
    /**
     * Modulele pot trimite un callback care, la rândul lui, va folosi Router-ul din core
     */
    function ccg_core_register_rest_routes( callable $callback ) {
        add_action( 'rest_api_init', $callback );
    }
}
