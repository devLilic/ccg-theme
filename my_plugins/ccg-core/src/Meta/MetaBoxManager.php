<?php
namespace CCG\Core\Meta;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MetaBoxManager {

    /**
     * @var MetaBox[]
     */
    protected static $metaboxes = [];

    /**
     * Modulele înregistrează metabox-urile aici.
     */
    public static function register_metabox( MetaBox $metabox ) {
        self::$metaboxes[] = $metabox;
    }

    /**
     * Punctul de pornire pentru metabox-uri.
     * Se cheamă DOAR în admin.
     */
    public static function init() {

        if ( ! is_admin() ) {
            return;
        }

        // când WordPress construiește sidebar-ul de meta box-uri
        add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_boxes_admin' ] );

        // când se salvează un post (orice post type)
        add_action( 'save_post', [ __CLASS__, 'save_metabox_data' ] );
    }

    /**
     * Înregistrăm efectiv metabox-urile în admin.
     */
    public static function register_meta_boxes_admin() {

        foreach ( self::$metaboxes as $metabox ) {

            foreach ( $metabox->screen as $screen ) {
                add_meta_box(
                    $metabox->id,
                    $metabox->title,
                    $metabox->callback,
                    $screen,
                    $metabox->context,
                    $metabox->priority
                );
            }
        }
    }

    /**
     * Lăsăm modulele să-și facă salvarea.
     * Fiecare metabox are propriul $save_callback (care verifică nonce, permisiuni, etc).
     */
    public static function save_metabox_data( $post_id ) {

        foreach ( self::$metaboxes as $metabox ) {
            call_user_func( $metabox->save_callback, $post_id );
        }
    }
}
