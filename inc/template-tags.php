<?php
/**
 * Template Tags – Calatorii Cu Gust Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Pagination wrapper (Tailwind)
 */
function ccg_pagination() {
    $args = array(
        'mid_size'           => 2,
        'prev_text'          => __('« Anterior'),
        'next_text'          => __('Următor »'),
        'screen_reader_text' => __('Navigare pagini'),
    );

    echo '<div class="ccg-pagination mt-8 text-center">';
    echo paginate_links( $args );
    echo '</div>';
}

/**
 * Eliminăm "continuă să citești" default
 */
function ccg_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'ccg_excerpt_more' );
