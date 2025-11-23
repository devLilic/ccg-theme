<?php
/**
 * Integrare CCG Gallery cu ccg-places.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Inițializare integrare ccg-places.
 */
function ccg_gallery_places_init() {
    // Shortcode: [ccg_place_gallery] sau [ccg_place_gallery id="123" columns="3"].
    add_shortcode( 'ccg_place_gallery', 'ccg_gallery_render_place_gallery_shortcode' );
}

/**
 * Shortcode wrapper pentru galerie place.
 *
 * @param array $atts Atribute.
 * @return string
 */
function ccg_gallery_render_place_gallery_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id'      => get_the_ID(),
            'columns' => 3,
        ),
        $atts,
        'ccg_place_gallery'
    );

    $post_id = isset( $atts['id'] ) ? (int) $atts['id'] : 0;

    if ( ! $post_id ) {
        return '';
    }

    $columns = (int) $atts['columns'];

    ob_start();
    ccg_gallery_output_place_gallery( $post_id, $columns );

    return ob_get_clean();
}

/**
 * Funcție de template pentru a randa galeria unui Place.
 *
 * Poate fi folosită din theme:
 *   if ( function_exists( 'ccg_gallery_output_place_gallery' ) ) {
 *       ccg_gallery_output_place_gallery( get_the_ID(), 3 );
 *   }
 *
 * @param int $post_id ID post place.
 * @param int $columns Număr de coloane pentru grid (doar ca helper CSS).
 */
function ccg_gallery_output_place_gallery( $post_id, $columns = 3 ) {
    $post_id = (int) $post_id;

    if ( ! $post_id ) {
        return;
    }

    $csv = get_post_meta( $post_id, '_ccg_place_gallery', true );

    if ( empty( $csv ) ) {
        return;
    }

    $ids_raw = explode( ',', $csv );
    $ids     = array();

    foreach ( $ids_raw as $id ) {
        $id = (int) trim( $id );
        if ( $id > 0 ) {
            $ids[] = $id;
        }
    }

    if ( empty( $ids ) ) {
        return;
    }

    $group   = 'place-' . $post_id;
    $columns = max( 1, (int) $columns );

    // Clasă helper pentru coloane – poate fi stilizată în CSS sau completată prin Tailwind în temă.
    $column_class = 'ccg-place-gallery-cols-' . $columns;

    ?>
    <div class="ccg-place-gallery ccg-gallery-grid <?php echo esc_attr( $column_class ); ?>">
        <?php
        $index = 0;

        foreach ( $ids as $attachment_id ) {
            $full_src_arr  = wp_get_attachment_image_src( $attachment_id, 'full' );
            $thumb_html    = wp_get_attachment_image(
                $attachment_id,
                'medium_large',
                false,
                array(
                    'class' => 'ccg-place-gallery-image',
                )
            );
            $full_src      = is_array( $full_src_arr ) ? $full_src_arr[0] : '';
            $alt           = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
            $caption       = wp_get_attachment_caption( $attachment_id );
            $caption_final = $caption ? $caption : $alt;

            if ( empty( $full_src ) ) {
                continue;
            }
            ?>
            <button
                type="button"
                class="ccg-gallery-item"
                data-ccg-gallery="<?php echo esc_attr( $group ); ?>"
                data-ccg-gallery-index="<?php echo esc_attr( $index ); ?>"
                data-ccg-gallery-src="<?php echo esc_url( $full_src ); ?>"
                <?php if ( ! empty( $caption_final ) ) : ?>
                    data-ccg-gallery-caption="<?php echo esc_attr( $caption_final ); ?>"
                <?php endif; ?>
            >
                <?php
                // Img HTML conține deja alt + class, filtrate de WP.
                echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            </button>
            <?php
            $index++;
        }
        ?>
    </div>
    <?php
}
