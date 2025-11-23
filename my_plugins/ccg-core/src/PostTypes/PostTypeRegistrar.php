<?php
namespace CCG\Core\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostTypeRegistrar {

    /**
     * Format:
     * [
     *   'partners' => [
     *       'args'   => [ ... ],
     *       'module' => 'ccg-partners'
     *   ],
     *   'places' => [ ... ],
     * ]
     *
     * @var array
     */
    protected static $queued = [];

    /**
     * Apelată de module: salvează CPT în coadă
     */
    public static function queue_post_type( $post_type, array $args, $module = '' ) {
        $post_type = sanitize_key( $post_type );
        if ( empty( $post_type ) ) {
            return;
        }

        self::$queued[ $post_type ] = [
            'args'   => $args,
            'module' => $module,
        ];
    }

    /**
     * Apelată pe 'init' (de Plugin.php) – înregistrează toate CPT-urile queued.
     */
    public static function register_all() {
        foreach ( self::$queued as $post_type => $data ) {
            $args = $data['args'];

            // Asigurăm câteva default-uri comune
            $defaults = [
                'public'       => true,
                'has_archive'  => true,
                'show_in_rest' => true,
                'supports'     => [ 'title', 'editor', 'thumbnail' ],
            ];

            $args = wp_parse_args( $args, $defaults );

            register_post_type( $post_type, $args );
        }
    }
}
