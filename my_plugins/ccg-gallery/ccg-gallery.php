<?php
/**
 * Plugin Name:       CCG Gallery
 * Description:       Central image gallery & lightbox plugin for CalatoriiCuGust.com
 * Version:           0.1.0
 * Author:            CalatoriiCuGust
 * Text Domain:       ccg-gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_GALLERY_VERSION', '0.1.0' );
define( 'CCG_GALLERY_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCG_GALLERY_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main init function for the plugin.
 */
function ccg_gallery_bootstrap() {
    // Core.
    require_once CCG_GALLERY_PATH . 'core/utils.php';
    require_once CCG_GALLERY_PATH . 'core/detector.php';
    require_once CCG_GALLERY_PATH . 'core/meta.php';
    require_once CCG_GALLERY_PATH . 'core/rest.php';
    require_once CCG_GALLERY_PATH . 'core/renderer.php';
    require_once CCG_GALLERY_PATH . 'core/lightbox.php';

    // Integrations.
    require_once CCG_GALLERY_PATH . 'integrations/post.php';
    require_once CCG_GALLERY_PATH . 'integrations/place.php';
    require_once CCG_GALLERY_PATH . 'integrations/events.php';
    require_once CCG_GALLERY_PATH . 'integrations/routes.php';

    // Register meta for all sources.
    add_action( 'init', 'ccg_gallery_register_meta_for_sources' );

    // Init REST & lightbox.
    add_action( 'rest_api_init', 'ccg_gallery_register_rest_routes' );
    add_action( 'wp_enqueue_scripts', 'ccg_gallery_enqueue_assets' );

    /**
     * Fired after ccg-gallery finished bootstrap.
     */
    do_action( 'ccg_gallery_init' );
}
add_action( 'plugins_loaded', 'ccg_gallery_bootstrap' );

