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


/**
 * Vite + Tailwind integration (din repo)
 */
require get_template_directory() . '/inc/inc.vite.php';

// Main switch to get frontend assets from a Vite dev server OR from production built folder
// it is recommended to move it into wp-config.php
define('IS_VITE_DEVELOPMENT', true);
