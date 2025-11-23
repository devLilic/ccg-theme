<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

// functions.php is empty so you can easily track what code is needed in order to Vite + Tailwind JIT run well
/**
 * Load theme setup (menus, supports, thumbnails, etc.)
 */
require get_template_directory() . '/inc/setup.php';


/**
 * Load enqueue scripts (CSS & JS)
 */
require get_template_directory() . '/inc/enqueue.php';


/**
 * Load template tags (pagination, helpers)
 */
require get_template_directory() . '/inc/template-tags.php';

require get_template_directory() . '/inc/customizer.php';




/**
 * Înregistrare sidebars (dacă vei avea)
 */
function ccg_register_sidebars() {
    register_sidebar( [
        'name'          => __( 'Sidebar principal', 'calatoriicugust' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );
}
add_action( 'widgets_init', 'ccg_register_sidebars' );


// ...

/**
 * Clase extra pentru item-urile din meniul primar (desktop)
 */
add_filter('nav_menu_css_class', function ($classes, $item, $args)
{
    if ($args->theme_location === 'primary') {
        // Toate item-urile din meniul primar devin relative
        $classes[] = 'ccg-menu-item';

        // Item cu submeniu – grup pentru hover
        if (in_array('menu-item-has-children', $item->classes, true)) {
            $classes[] = 'ccg-menu-item-has-children';
        }
    }

    return $classes;
}, 10, 3);

function add_meta_tags() {
    global $post;
    if ( is_single() ) {
        $meta = strip_tags( $post->post_content );
        $meta = strip_shortcodes( $meta );
        $meta = str_replace( array("\n", "\r", "\t"), ' ', $meta );
        $meta = substr( $meta, 0, 130 );
        $keywords = get_the_category( $post->ID );
        $metakeywords = '';
        foreach ( $keywords as $keyword ) {
            $metakeywords .= $keyword->cat_name . ", ";
        }

        echo '<meta name="description" content="' . $meta . '" />' . "\n";
        echo '<meta name="keywords" content="' . $metakeywords . '" />' . "\n";
        echo '<meta property="og:title" content="'.get_the_title($post).'"/>'."\n";
        echo '<meta property="og:type" content="website"/>'."\n";
        echo '<meta property="og:url" content="'.get_permalink($post).'"/>'."\n";
        echo '<meta property="og:image" content="'.get_the_post_thumbnail_url().'"/>'."\n";
        echo '<meta property="og:description" content="'.$meta.'"/>';
    }
}
add_action( 'wp_head', 'add_meta_tags' , 2 );

function ccg_enqueue_leaflet() {
    if (is_singular('place')) {
        wp_enqueue_style(
            'leaflet-css',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            [],
            null
        );

        wp_enqueue_script(
            'leaflet-js',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            [],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ccg_enqueue_leaflet');


/**
 * Vite + Tailwind integration (din repo)
 */
require get_template_directory() . '/inc/inc.vite.php';
//
//// Main switch to get frontend assets from a Vite dev server OR from production built folder
//// it is recommended to move it into wp-config.php
define('IS_VITE_DEVELOPMENT', true);
