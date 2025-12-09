<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Recursive seeder for multidimensional arrays.
 *
 * @param string $taxonomy
 * @param array $data
 * @param int|null $parent
 */
function ccg_taxmgr_execute_seed_array( $taxonomy, $data, $parent = 0 ) {

    foreach ( $data as $key => $value ) {

        // CASE 1: ["Termen 1", "Termen 2"]
        if ( is_int( $key ) && is_string( $value ) ) {

            if ( ! term_exists( $value, $taxonomy ) ) {
                $result = wp_insert_term(
                    $value,
                    $taxonomy,
                    [
                        'parent' => $parent,
                        'slug'   => sanitize_title( $value )
                    ]
                );
                continue;
            }
        }

        // CASE 2: "Parent" => [...]
        if ( is_string( $key ) && is_array( $value ) ) {

            // Create parent
            $parent_term = term_exists( $key, $taxonomy );
            if ( ! $parent_term ) {
                $parent_term = wp_insert_term(
                    $key,
                    $taxonomy,
                    [
                        'parent' => $parent,
                        'slug'   => sanitize_title( $key )
                    ]
                );
            }

            if ( ! is_wp_error( $parent_term ) ) {
                $parent_id = $parent_term['term_id'];
                ccg_taxmgr_execute_seed_array( $taxonomy, $value, $parent_id );
            }

            continue;
        }

        // CASE 3: key = value = simple string
        if ( is_string( $key ) && is_string( $value ) ) {
            if ( ! term_exists( $value, $taxonomy ) ) {
                wp_insert_term(
                    $value,
                    $taxonomy,
                    [
                        'parent' => $parent,
                        'slug'   => sanitize_title( $value )
                    ]
                );
            }
            continue;
        }
    }
}
