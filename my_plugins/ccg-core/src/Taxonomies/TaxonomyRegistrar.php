<?php
namespace CCG\Core\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TaxonomyRegistrar {

    protected static $queued = [];

    public static function queue_taxonomy( $taxonomy, $object_type, array $args, $module = '' ) {
        $taxonomy   = sanitize_key( $taxonomy );
        $object_type = (array) $object_type;

        if ( empty( $taxonomy ) || empty( $object_type ) ) {
            return;
        }

        self::$queued[ $taxonomy ] = [
            'object_type' => $object_type,
            'args'        => $args,
            'module'      => $module,
        ];
    }

    public static function register_all() {
        foreach ( self::$queued as $taxonomy => $data ) {
            $args = $data['args'];

            $defaults = [
                'public'       => true,
                'show_in_rest' => true,
                'hierarchical' => true,
            ];

            $args = wp_parse_args( $args, $defaults );

            register_taxonomy( $taxonomy, $data['object_type'], $args );
        }
    }
}
