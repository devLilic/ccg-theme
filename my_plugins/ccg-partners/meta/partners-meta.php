<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use CCG\Core\Meta\MetaBox;
use CCG\Core\Helpers\Sanitizer;

/**
 * Înregistrăm metabox-ul prin ccg-core.
 */
function ccg_partners_register_metabox() {

    if ( ! class_exists( '\CCG\Core\Meta\MetaBox' ) ) {
        return;
    }

    $box = new MetaBox(
        'ccg_partners_box',
        __( 'Detalii Partener / Sponsor', 'ccg-partners' ),
        [ 'partners' ],
        'ccg_partners_render_metabox',
        'ccg_partners_save_metabox',
        'normal',
        'high'
    );

    if ( function_exists( 'ccg_core_register_metabox' ) ) {
        ccg_core_register_metabox( $box );
    }
}

/**
 * HTML Metabox.
 */
function ccg_partners_render_metabox( $post ) {

    $banner_id = get_post_meta( $post->ID, '_ccg_banner_id', true );
    $site_url  = get_post_meta( $post->ID, '_ccg_site_url', true );
    $type      = get_post_meta( $post->ID, '_ccg_type', true );
    $tier      = get_post_meta( $post->ID, '_ccg_partner_tier', true );
    $published = get_post_meta( $post->ID, '_ccg_published', true );

    wp_enqueue_media();

    wp_nonce_field( 'ccg_partners_meta_save', 'ccg_partners_meta_nonce' );
    ?>
    <style>
        .ccg-banner-preview img {
            max-width: 200px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 4px;
            background: #fff;
        }
    </style>

    <p>
        <label><strong><?php esc_html_e( 'Banner imagine', 'ccg-partners' ); ?></strong></label><br>
    <div class="ccg-banner-preview">
        <?php
        if ( $banner_id ) {
            echo wp_get_attachment_image( $banner_id, 'medium' );
        }
        ?>
    </div>

    <input type="hidden" id="ccg_banner_id" name="ccg_banner_id" value="<?php echo esc_attr( $banner_id ); ?>">

    <button type="button" class="button" id="ccg_banner_button">
        <?php esc_html_e( 'Selectează imagine', 'ccg-partners' ); ?>
    </button>
    <button type="button" class="button" id="ccg_banner_remove" style="color:red;">
        <?php esc_html_e( 'Șterge', 'ccg-partners' ); ?>
    </button>
    </p>

    <p>
        <label><strong><?php esc_html_e( 'Link spre site', 'ccg-partners' ); ?></strong></label>
        <input type="text"
               name="ccg_site_url"
               value="<?php echo esc_attr( $site_url ); ?>"
               class="widefat"
               placeholder="https://exemplu.com/">
    </p>

    <p>
        <label><strong><?php esc_html_e( 'Tip', 'ccg-partners' ); ?></strong></label>
        <select name="ccg_type" class="widefat">
            <option value="partner" <?php selected( $type, 'partner' ); ?>>
                <?php esc_html_e( 'Partener', 'ccg-partners' ); ?>
            </option>
            <option value="sponsor" <?php selected( $type, 'sponsor' ); ?>>
                <?php esc_html_e( 'Sponsor', 'ccg-partners' ); ?>
            </option>
        </select>
    </p>

    <p>
        <label><strong><?php esc_html_e( 'Categorie Partener', 'ccg-partners' ); ?></strong></label>
        <select name="ccg_partner_tier" class="widefat">
            <option value="standard" <?php selected( $tier, 'standard' ); ?>>
                <?php esc_html_e( 'Standard', 'ccg-partners' ); ?>
            </option>
            <option value="gold" <?php selected( $tier, 'gold' ); ?>>
                <?php esc_html_e( 'Gold', 'ccg-partners' ); ?>
            </option>
            <option value="silver" <?php selected( $tier, 'silver' ); ?>>
                <?php esc_html_e( 'Silver', 'ccg-partners' ); ?>
            </option>
        </select>
    </p>

    <p>
        <label><strong><?php esc_html_e( 'Publicat?', 'ccg-partners' ); ?></strong></label>
        <select name="ccg_published" class="widefat">
            <option value="1" <?php selected( $published, '1' ); ?>>
                <?php esc_html_e( 'Da', 'ccg-partners' ); ?>
            </option>
            <option value="0" <?php selected( $published, '0' ); ?>>
                <?php esc_html_e( 'Nu', 'ccg-partners' ); ?>
            </option>
        </select>
    </p>

    <script>
        jQuery(document).ready(function($){

            var frame;

            $('#ccg_banner_button').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php echo esc_js( __( 'Selectează imagine banner', 'ccg-partners' ) ); ?>',
                    button: { text: '<?php echo esc_js( __( 'Folosește imaginea', 'ccg-partners' ) ); ?>' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#ccg_banner_id').val(attachment.id);
                    $('.ccg-banner-preview').html('<img src="' + attachment.url + '" />');
                });

                frame.open();
            });

            $('#ccg_banner_remove').on('click', function() {
                $('#ccg_banner_id').val('');
                $('.ccg-banner-preview').html('');
            });

        });
    </script>

    <?php
}

/**
 * Salvăm meta-urile.
 */
function ccg_partners_save_metabox( $post_id ) {

    // Nonce check
    if ( ! isset( $_POST['ccg_partners_meta_nonce'] ) ||
        ! wp_verify_nonce( $_POST['ccg_partners_meta_nonce'], 'ccg_partners_meta_save' ) ) {
        return;
    }

    // Permisiuni
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Autosave?
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Banner
    if ( isset( $_POST['ccg_banner_id'] ) && $_POST['ccg_banner_id'] !== '' ) {
        $banner_id = Sanitizer::text( $_POST['ccg_banner_id'] );
        update_post_meta( $post_id, '_ccg_banner_id', $banner_id );
    } else {
        delete_post_meta( $post_id, '_ccg_banner_id' );
    }

    // URL site
    if ( isset( $_POST['ccg_site_url'] ) ) {
        $url = Sanitizer::url( $_POST['ccg_site_url'] );
        update_post_meta( $post_id, '_ccg_site_url', $url );
    }

    // Tip
    if ( isset( $_POST['ccg_type'] ) ) {
        $type = Sanitizer::select( $_POST['ccg_type'], [ 'partner', 'sponsor' ] );
        update_post_meta( $post_id, '_ccg_type', $type );
    }

    // Tier
    if ( isset( $_POST['ccg_partner_tier'] ) ) {
        $tier = Sanitizer::select( $_POST['ccg_partner_tier'], [ 'standard', 'gold', 'silver' ] );
        update_post_meta( $post_id, '_ccg_partner_tier', $tier );
    }

    // Status
    if ( isset( $_POST['ccg_published'] ) ) {
        $published = Sanitizer::select( $_POST['ccg_published'], [ '0', '1' ] );
        update_post_meta( $post_id, '_ccg_published', $published );
    }
}
