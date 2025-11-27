<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use CCG\Core\Meta\MetaBox;
use CCG\Core\Helpers\Sanitizer;


/**
 * Înregistrăm metabox-ul prin ccg-core.
 */
function ccg_places_register_metabox() {

    if ( ! class_exists( '\CCG\Core\Meta\MetaBox' ) ) {
        return;
    }

    $box = new MetaBox(
            'ccg_places_box',
            __( 'Detalii Locație Turistică', 'ccg-places' ),
            [ 'place' ],
            'ccg_places_render_metabox',
            'ccg_places_save_metabox',
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
function ccg_places_render_metabox( $post ) {

    $short_desc   = get_post_meta( $post->ID, '_ccg_place_short_description', true );
    $lat          = get_post_meta( $post->ID, '_ccg_place_lat', true );
    $lng          = get_post_meta( $post->ID, '_ccg_place_lng', true );

    // NOU: center & zoom pentru hartă
    $map_center_lat = get_post_meta( $post->ID, '_ccg_place_map_center_lat', true );
    $map_center_lng = get_post_meta( $post->ID, '_ccg_place_map_center_lng', true );
    $map_zoom       = get_post_meta( $post->ID, '_ccg_place_map_zoom', true );

    $gallery      = get_post_meta( $post->ID, '_ccg_place_gallery', true );
    $hours        = get_post_meta( $post->ID, '_ccg_place_opening_hours', true );
    $duration     = get_post_meta( $post->ID, '_ccg_place_visit_duration', true );
    $best_season  = get_post_meta( $post->ID, '_ccg_place_best_season', true );
    $recommended  = get_post_meta( $post->ID, '_ccg_place_recommended_for', true );
    $access       = get_post_meta( $post->ID, '_ccg_place_access', true );
    $price_range  = get_post_meta( $post->ID, '_ccg_place_price_range', true );
    $website      = get_post_meta( $post->ID, '_ccg_place_contact_website', true );
    $phone        = get_post_meta( $post->ID, '_ccg_place_contact_phone', true );
    $email        = get_post_meta( $post->ID, '_ccg_place_contact_email', true );
    $social       = get_post_meta( $post->ID, '_ccg_place_contact_social', true );
    $booking_url  = get_post_meta( $post->ID, '_ccg_place_booking_url', true );

    $rel_events   = get_post_meta( $post->ID, '_ccg_place_related_events', true );
    $rel_routes   = get_post_meta( $post->ID, '_ccg_place_related_routes', true );
    $rel_wine     = get_post_meta( $post->ID, '_ccg_place_related_wineries', true );

    $gallery_ids  = $gallery ? array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) : [];

    $recommended_arr = $recommended ? array_filter( array_map( 'trim', explode( ',', $recommended ) ) ) : [];
    $access_arr      = $access ? array_filter( array_map( 'trim', explode( ',', $access ) ) ) : [];

    $duration_options = [
            ''         => __( '— selectează —', 'ccg-places' ),
            '30m'      => __( '30 minute', 'ccg-places' ),
            '1h'       => __( '1 oră', 'ccg-places' ),
            'half_day' => __( 'Jumătate de zi', 'ccg-places' ),
            'full_day' => __( 'O zi întreagă', 'ccg-places' ),
            '2_plus'   => __( '2+ zile', 'ccg-places' ),
    ];

    $season_options = [
            ''         => __( '— selectează —', 'ccg-places' ),
            'all_year' => __( 'Tot anul', 'ccg-places' ),
            'spring'   => __( 'Primăvara', 'ccg-places' ),
            'summer'   => __( 'Vara', 'ccg-places' ),
            'autumn'   => __( 'Toamna', 'ccg-places' ),
            'winter'   => __( 'Iarna', 'ccg-places' ),
    ];

    $recommended_options = [
            'families'      => __( 'Familii', 'ccg-places' ),
            'hikers'        => __( 'Drumeți', 'ccg-places' ),
            'food_lovers'   => __( 'Amatori de gastronomie', 'ccg-places' ),
            'photographers' => __( 'Fotografi', 'ccg-places' ),
            'pilgrims'      => __( 'Pelerini', 'ccg-places' ),
            'cyclists'      => __( 'Cicliști', 'ccg-places' ),
            'nature_lovers' => __( 'Iubitori de natură', 'ccg-places' ),
            'adventure'     => __( 'Aventură', 'ccg-places' ),
    ];

    $access_options = [
            'car'   => __( 'Mașină', 'ccg-places' ),
            'bus'   => __( 'Autobuz', 'ccg-places' ),
            'train' => __( 'Tren', 'ccg-places' ),
            'boat'  => __( 'Barcă', 'ccg-places' ),
            'bike'  => __( 'Bicicletă', 'ccg-places' ),
    ];

    $price_options = [
            ''         => __( '— nedefinit —', 'ccg-places' ),
            'free'     => __( 'Gratuit', 'ccg-places' ),
            'paid'     => __( 'Plătit', 'ccg-places' ),
            'moderate' => __( 'Moderat', 'ccg-places' ),
            'premium'  => __( 'Premium', 'ccg-places' ),
    ];

    wp_enqueue_media();
    wp_nonce_field( 'ccg_places_meta_save', 'ccg_places_meta_nonce' );

    // Moldova default center
    $default_center_lat = $map_center_lat ? $map_center_lat : '47.0';
    $default_center_lng = $map_center_lng ? $map_center_lng : '28.8';
    $default_zoom       = $map_zoom ? (int) $map_zoom : ( $lat && $lng ? 11 : 7 );
    ?>

    <div class="ccg-meta-wrapper">

        <div class="ccg-field-group">
            <label for="ccg_place_short_description"><strong><?php esc_html_e( 'Descriere scurtă', 'ccg-places' ); ?></strong></label>
            <textarea id="ccg_place_short_description"
                      name="ccg_place_short_description"
                      class="widefat"
                      rows="3"
                      placeholder="<?php esc_attr_e( '1–2 fraze folosite în carduri, hartă și listări.', 'ccg-places' ); ?>"><?php echo esc_textarea( $short_desc ); ?></textarea>
        </div>

        <div class="ccg-field-group">
            <label for="ccg_place_map_search"><strong><?php esc_html_e( 'Căutare pe hartă', 'ccg-places' ); ?></strong></label>
            <div class="ccg-meta-two-cols">
                <input type="text"
                       id="ccg_place_map_search"
                       class="widefat"
                       placeholder="<?php esc_attr_e( 'Ex: Orheiul Vechi, Moldova', 'ccg-places' ); ?>">
                <button type="button" class="button" id="ccg_place_map_search_btn">
                    <?php esc_html_e( 'Caută', 'ccg-places' ); ?>
                </button>
            </div>
            <p class="description">
                <?php esc_html_e( 'Se folosește geocodare OpenStreetMap (Nominatim) pentru a poziționa harta.', 'ccg-places' ); ?>
            </p>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Coordonate GPS', 'ccg-places' ); ?></strong></label>
            <p class="description">
                <?php esc_html_e( 'Poți fie să faci click pe hartă, fie să introduci manual latitudine și longitudine. Poți ajusta apoi poziția și zoom-ul hărții — acestea vor fi folosite și în pagina publică.', 'ccg-places' ); ?>
            </p>

            <div id="ccg_place_map"
                 data-center-lat="<?php echo esc_attr( $default_center_lat ); ?>"
                 data-center-lng="<?php echo esc_attr( $default_center_lng ); ?>"
                 data-center-zoom="<?php echo esc_attr( $default_zoom ); ?>"
                 data-marker-lat="<?php echo esc_attr( $lat ); ?>"
                 data-marker-lng="<?php echo esc_attr( $lng ); ?>"></div>

            <div class="ccg-meta-two-cols ccg-meta-coords">
                <div>
                    <label for="ccg_place_lat"><?php esc_html_e( 'Latitudine', 'ccg-places' ); ?></label>
                    <input type="text"
                           id="ccg_place_lat"
                           name="ccg_place_lat"
                           value="<?php echo esc_attr( $lat ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_place_lng"><?php esc_html_e( 'Longitudine', 'ccg-places' ); ?></label>
                    <input type="text"
                           id="ccg_place_lng"
                           name="ccg_place_lng"
                           value="<?php echo esc_attr( $lng ); ?>"
                           class="widefat">
                </div>
            </div>

            <!-- câmpuri ascunse pentru center + zoom -->
            <input type="hidden" id="ccg_place_map_center_lat" name="ccg_place_map_center_lat" value="<?php echo esc_attr( $map_center_lat ); ?>">
            <input type="hidden" id="ccg_place_map_center_lng" name="ccg_place_map_center_lng" value="<?php echo esc_attr( $map_center_lng ); ?>">
            <input type="hidden" id="ccg_place_map_zoom"       name="ccg_place_map_zoom"       value="<?php echo esc_attr( $map_zoom ); ?>">
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Galerie imagini', 'ccg-places' ); ?></strong></label>

            <div class="ccg-gallery-grid" id="ccg_place_gallery_preview">
                <?php
                if ( ! empty( $gallery_ids ) ) {
                    foreach ( $gallery_ids as $img_id ) {
                        echo wp_get_attachment_image( $img_id, [ 80, 80 ] );
                    }
                }
                ?>
            </div>

            <input type="hidden" id="ccg_place_gallery" name="ccg_place_gallery" value="<?php echo esc_attr( $gallery ); ?>">

            <button type="button" class="button" id="ccg_place_gallery_button">
                <?php esc_html_e( 'Selectează imagini', 'ccg-places' ); ?>
            </button>
            <button type="button" class="button" id="ccg_place_gallery_clear" style="color:red;">
                <?php esc_html_e( 'Șterge galeria', 'ccg-places' ); ?>
            </button>
        </div>

        <div class="ccg-field-group">
            <label for="ccg_place_opening_hours"><strong><?php esc_html_e( 'Program / Detalii vizitare', 'ccg-places' ); ?></strong></label>
            <textarea id="ccg_place_opening_hours"
                      name="ccg_place_opening_hours"
                      class="widefat"
                      rows="4"
                      placeholder="<?php esc_attr_e( 'Ex: L–V 09:00–18:00, S–D 10:00–16:00. Închis în zilele de sărbătoare.', 'ccg-places' ); ?>"><?php echo esc_textarea( $hours ); ?></textarea>
        </div>

        <div class="ccg-meta-two-cols">
            <div class="ccg-field-group">
                <label for="ccg_place_visit_duration"><strong><?php esc_html_e( 'Durata recomandată a vizitei', 'ccg-places' ); ?></strong></label>
                <select id="ccg_place_visit_duration" name="ccg_place_visit_duration" class="widefat">
                    <?php foreach ( $duration_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $duration, $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ccg-field-group">
                <label for="ccg_place_best_season"><strong><?php esc_html_e( 'Sezon recomandat', 'ccg-places' ); ?></strong></label>
                <select id="ccg_place_best_season" name="ccg_place_best_season" class="widefat">
                    <?php foreach ( $season_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $best_season, $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Recomandat pentru', 'ccg-places' ); ?></strong></label>
            <div class="ccg-checkbox-grid">
                <?php foreach ( $recommended_options as $value => $label ) : ?>
                    <label>
                        <input type="checkbox"
                               name="ccg_place_recommended_for[]"
                               value="<?php echo esc_attr( $value ); ?>"
                                <?php checked( in_array( $value, $recommended_arr, true ) ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Acces', 'ccg-places' ); ?></strong></label>
            <div class="ccg-checkbox-grid">
                <?php foreach ( $access_options as $value => $label ) : ?>
                    <label>
                        <input type="checkbox"
                               name="ccg_place_access[]"
                               value="<?php echo esc_attr( $value ); ?>"
                                <?php checked( in_array( $value, $access_arr, true ) ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ccg-meta-two-cols">
            <div class="ccg-field-group">
                <label for="ccg_place_price_range"><strong><?php esc_html_e( 'Interval de preț', 'ccg-places' ); ?></strong></label>
                <select id="ccg_place_price_range" name="ccg_place_price_range" class="widefat">
                    <?php foreach ( $price_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $price_range, $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ccg-field-group">
                <label for="ccg_place_booking_url"><strong><?php esc_html_e( 'Link rezervare (booking)', 'ccg-places' ); ?></strong></label>
                <input type="url"
                       id="ccg_place_booking_url"
                       name="ccg_place_booking_url"
                       value="<?php echo esc_attr( $booking_url ); ?>"
                       class="widefat"
                       placeholder="https://exemplu.com/rezervare">
            </div>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Contact', 'ccg-places' ); ?></strong></label>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_place_contact_website"><?php esc_html_e( 'Website', 'ccg-places' ); ?></label>
                    <input type="url"
                           id="ccg_place_contact_website"
                           name="ccg_place_contact_website"
                           value="<?php echo esc_attr( $website ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_place_contact_phone"><?php esc_html_e( 'Telefon', 'ccg-places' ); ?></label>
                    <input type="text"
                           id="ccg_place_contact_phone"
                           name="ccg_place_contact_phone"
                           value="<?php echo esc_attr( $phone ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_place_contact_email"><?php esc_html_e( 'Email', 'ccg-places' ); ?></label>
                    <input type="email"
                           id="ccg_place_contact_email"
                           name="ccg_place_contact_email"
                           value="<?php echo esc_attr( $email ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_place_contact_social"><?php esc_html_e( 'Social / Note contact', 'ccg-places' ); ?></label>
                    <textarea id="ccg_place_contact_social"
                              name="ccg_place_contact_social"
                              class="widefat"
                              rows="2"><?php echo esc_textarea( $social ); ?></textarea>
                </div>
            </div>
        </div>

        <div class="ccg-field-group">
            <label for="ccg_place_related_events"><strong><?php esc_html_e( 'Evenimente asociate (ID-uri, separate prin virgulă)', 'ccg-places' ); ?></strong></label>
            <input type="text"
                   id="ccg_place_related_events"
                   name="ccg_place_related_events"
                   value="<?php echo esc_attr( $rel_events ); ?>"
                   class="widefat"
                   placeholder="ex: 123, 456">
        </div>

        <div class="ccg-field-group">
            <label for="ccg_place_related_routes"><strong><?php esc_html_e( 'Rute turistice asociate (ID-uri)', 'ccg-places' ); ?></strong></label>
            <input type="text"
                   id="ccg_place_related_routes"
                   name="ccg_place_related_routes"
                   value="<?php echo esc_attr( $rel_routes ); ?>"
                   class="widefat"
                   placeholder="ex: 11, 22">
        </div>

        <div class="ccg-field-group">
            <label for="ccg_place_related_wineries"><strong><?php esc_html_e( 'Crame asociate (ID-uri)', 'ccg-places' ); ?></strong></label>
            <input type="text"
                   id="ccg_place_related_wineries"
                   name="ccg_place_related_wineries"
                   value="<?php echo esc_attr( $rel_wine ); ?>"
                   class="widefat"
                   placeholder="ex: 77, 88">
        </div>

    </div>

    <?php
}

/**
 * Salvare meta.
 */
function ccg_places_save_metabox( $post_id ) {

    if ( ! isset( $_POST['ccg_places_meta_nonce'] ) ||
            ! wp_verify_nonce( $_POST['ccg_places_meta_nonce'], 'ccg_places_meta_save' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Descriere scurtă
    if ( isset( $_POST['ccg_place_short_description'] ) ) {
        $short = sanitize_textarea_field( $_POST['ccg_place_short_description'] );
        update_post_meta( $post_id, '_ccg_place_short_description', $short );
    }

    // Lat / Lng (marker)
    if ( isset( $_POST['ccg_place_lat'] ) ) {
        $lat = Sanitizer::coord( $_POST['ccg_place_lat'] );
        update_post_meta( $post_id, '_ccg_place_lat', $lat );
    }

    if ( isset( $_POST['ccg_place_lng'] ) ) {
        $lng = Sanitizer::coord( $_POST['ccg_place_lng'] );
        update_post_meta( $post_id, '_ccg_place_lng', $lng );
    }

    // Center & zoom hartă (pentru afișare în single)
    if ( isset( $_POST['ccg_place_map_center_lat'] ) ) {
        $clat = Sanitizer::coord( $_POST['ccg_place_map_center_lat'] );
        update_post_meta( $post_id, '_ccg_place_map_center_lat', $clat );
    }
    if ( isset( $_POST['ccg_place_map_center_lng'] ) ) {
        $clng = Sanitizer::coord( $_POST['ccg_place_map_center_lng'] );
        update_post_meta( $post_id, '_ccg_place_map_center_lng', $clng );
    }
    if ( isset( $_POST['ccg_place_map_zoom'] ) ) {
        $zoom = (int) $_POST['ccg_place_map_zoom'];
        update_post_meta( $post_id, '_ccg_place_map_zoom', $zoom );
    }

    // Galerie
    if ( isset( $_POST['ccg_place_gallery'] ) ) {
        $raw = sanitize_text_field( $_POST['ccg_place_gallery'] );
        $ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
        $csv = implode( ',', $ids );
        update_post_meta( $post_id, '_ccg_place_gallery', $csv );
    }

    // Program
    if ( isset( $_POST['ccg_place_opening_hours'] ) ) {
        $hours = wp_kses_post( $_POST['ccg_place_opening_hours'] );
        update_post_meta( $post_id, '_ccg_place_opening_hours', $hours );
    }

    // Durată
    if ( isset( $_POST['ccg_place_visit_duration'] ) ) {
        $duration = Sanitizer::select(
                $_POST['ccg_place_visit_duration'],
                [ '', '30m', '1h', 'half_day', 'full_day', '2_plus' ]
        );
        update_post_meta( $post_id, '_ccg_place_visit_duration', $duration );
    }

    // Sezon
    if ( isset( $_POST['ccg_place_best_season'] ) ) {
        $season = Sanitizer::select(
                $_POST['ccg_place_best_season'],
                [ '', 'all_year', 'spring', 'summer', 'autumn', 'winter' ]
        );
        update_post_meta( $post_id, '_ccg_place_best_season', $season );
    }

    // Recomandat pentru
    if ( isset( $_POST['ccg_place_recommended_for'] ) && is_array( $_POST['ccg_place_recommended_for'] ) ) {
        $allowed = [ 'families', 'hikers', 'food_lovers', 'photographers', 'pilgrims', 'cyclists', 'nature_lovers', 'adventure' ];
        $vals    = array_intersect( array_map( 'sanitize_text_field', $_POST['ccg_place_recommended_for'] ), $allowed );
        $csv     = implode( ',', $vals );
        update_post_meta( $post_id, '_ccg_place_recommended_for', $csv );
    } else {
        delete_post_meta( $post_id, '_ccg_place_recommended_for' );
    }

    // Acces
    if ( isset( $_POST['ccg_place_access'] ) && is_array( $_POST['ccg_place_access'] ) ) {
        $allowed = [ 'car', 'bus', 'train', 'boat', 'bike' ];
        $vals    = array_intersect( array_map( 'sanitize_text_field', $_POST['ccg_place_access'] ), $allowed );
        $csv     = implode( ',', $vals );
        update_post_meta( $post_id, '_ccg_place_access', $csv );
    } else {
        delete_post_meta( $post_id, '_ccg_place_access' );
    }

    // Preț
    if ( isset( $_POST['ccg_place_price_range'] ) ) {
        $price = Sanitizer::select(
                $_POST['ccg_place_price_range'],
                [ '', 'free', 'paid', 'moderate', 'premium' ]
        );
        update_post_meta( $post_id, '_ccg_place_price_range', $price );
    }

    // Contact & booking
    if ( isset( $_POST['ccg_place_contact_website'] ) ) {
        $url = Sanitizer::url( $_POST['ccg_place_contact_website'] );
        update_post_meta( $post_id, '_ccg_place_contact_website', $url );
    }

    if ( isset( $_POST['ccg_place_contact_phone'] ) ) {
        $phone = sanitize_text_field( $_POST['ccg_place_contact_phone'] );
        update_post_meta( $post_id, '_ccg_place_contact_phone', $phone );
    }

    if ( isset( $_POST['ccg_place_contact_email'] ) ) {
        $email = sanitize_email( $_POST['ccg_place_contact_email'] );
        update_post_meta( $post_id, '_ccg_place_contact_email', $email );
    }

    if ( isset( $_POST['ccg_place_contact_social'] ) ) {
        $social = sanitize_textarea_field( $_POST['ccg_place_contact_social'] );
        update_post_meta( $post_id, '_ccg_place_contact_social', $social );
    }

    if ( isset( $_POST['ccg_place_booking_url'] ) ) {
        $url = Sanitizer::url( $_POST['ccg_place_booking_url'] );
        update_post_meta( $post_id, '_ccg_place_booking_url', $url );
    }

    // Relații simple
    foreach ( [
                      'ccg_place_related_events'   => '_ccg_place_related_events',
                      'ccg_place_related_routes'   => '_ccg_place_related_routes',
                      'ccg_place_related_wineries' => '_ccg_place_related_wineries',
              ] as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            $raw = sanitize_text_field( $_POST[ $field ] );
            update_post_meta( $post_id, $meta_key, $raw );
        }
    }
}
