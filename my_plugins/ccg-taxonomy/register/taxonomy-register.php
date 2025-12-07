<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Înregistrează taxonomiile definite în CPT-ul ccg_taxonomy.
 */
function ccg_taxmgr_register_dynamic_taxonomies() {


    $tax_posts = get_posts([
        'post_type'      => 'ccg_taxonomy',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'no_found_rows'  => true,
    ]);

    if ( empty( $tax_posts ) ) {
        return;
    }

    foreach ( $tax_posts as $tax_post ) {

        $id = $tax_post->ID;

        $slug         = get_post_meta( $id, '_ccgtax_slug', true );
        $plural       = get_post_meta( $id, '_ccgtax_label_plural', true );
        $singular     = get_post_meta( $id, '_ccgtax_label_singular', true );
        $menu_name    = get_post_meta( $id, '_ccgtax_label_menu', true );
        $desc         = get_post_meta( $id, '_ccgtax_description', true );

        $public       = get_post_meta( $id, '_ccgtax_public', true ) === '1';
        $hierarchical = get_post_meta( $id, '_ccgtax_hierarchical', true ) === '1';
        $show_col     = get_post_meta( $id, '_ccgtax_show_admin_column', true ) === '1';
        $show_rest    = get_post_meta( $id, '_ccgtax_show_in_rest', true ) === '1';

        $pts_csv = get_post_meta( $id, '_ccgtax_post_types', true );
        $post_types = $pts_csv ? array_filter( array_map('trim', explode(',', $pts_csv))) : [];

        if ( empty($post_types) ) {
            continue;
        }

        if ( empty($slug) ) {
            $slug = sanitize_title( $tax_post->post_title );
        }

        $labels = [
            'name'              => $plural,
            'singular_name'     => $singular,
            'search_items'      => "Caută $plural",
            'all_items'         => "Toate $plural",
            'edit_item'         => "Editează $singular",
            'update_item'       => "Actualizează $singular",
            'add_new_item'      => "Adaugă $singular Nou",
            'new_item_name'     => "Nume $singular Nou",
            'menu_name'         => $menu_name,
        ];

        $args = [
            'labels'            => $labels,
            'description'       => $desc,
            'public'            => $public,
            'hierarchical'      => $hierarchical,
            'show_admin_column' => $show_col,
            'show_in_rest'      => $show_rest,
        ];


        // If ccg-core function exists, use it – otherwise fallback
        if ( function_exists('ccg_core_register_taxonomy') ) {
            ccg_core_register_taxonomy($slug, $post_types, $args, 'ccg-taxonomy');
        } else {
            register_taxonomy($slug, $post_types, $args);
        }

    }

}
