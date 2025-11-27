<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use CCG\Core\Meta\MetaBox;
require_once CCG_EVENTS_PATH . 'meta/events-map.php';

/**
 * Înregistrăm metabox-ul prin ccg-core.
 */
function ccg_events_register_metabox() {

    if ( ! class_exists( '\CCG\Core\Meta\MetaBox' ) ) {
        return;
    }

    $box = new MetaBox(
        'ccg_events_box',
        __( 'Detalii Eveniment', 'ccg-events' ),
        [ 'event' ],
        'ccg_events_render_metabox',
        'ccg_events_save_metabox',
        'normal',
        'high'
    );

    if ( function_exists( 'ccg_core_register_metabox' ) ) {
        ccg_core_register_metabox( $box );
    }
}

/**
 * Render metabox.
 */
function ccg_events_render_metabox( $post ) {

    $short_desc   = get_post_meta( $post->ID, '_ccg_event_short_description', true );

    // Date & time
    $date_start   = get_post_meta( $post->ID, '_ccg_event_date_start', true );
    $time_start   = get_post_meta( $post->ID, '_ccg_event_time_start', true );
    $date_end     = get_post_meta( $post->ID, '_ccg_event_date_end', true );
    $time_end     = get_post_meta( $post->ID, '_ccg_event_time_end', true );
    $is_one_day   = get_post_meta( $post->ID, '_ccg_event_is_one_day', true );
    $recurrence   = get_post_meta( $post->ID, '_ccg_event_recurrence', true );
    $rec_pattern  = get_post_meta( $post->ID, '_ccg_event_recurrence_pattern', true );

    // Location
    $address      = get_post_meta( $post->ID, '_ccg_event_address', true );
    $locality     = get_post_meta( $post->ID, '_ccg_event_locality', true );
    $lat          = get_post_meta( $post->ID, '_ccg_event_lat', true );
    $lng          = get_post_meta( $post->ID, '_ccg_event_lng', true );

    // Related place
    $related_place = (int) get_post_meta( $post->ID, '_ccg_event_related_place', true );

    // Organizer
    $org_name     = get_post_meta( $post->ID, '_ccg_event_organizer_name', true );
    $org_site     = get_post_meta( $post->ID, '_ccg_event_organizer_website', true );
    $org_phone    = get_post_meta( $post->ID, '_ccg_event_organizer_phone', true );
    $org_email    = get_post_meta( $post->ID, '_ccg_event_organizer_email', true );
    $fb_event     = get_post_meta( $post->ID, '_ccg_event_facebook', true );
    $ig_event     = get_post_meta( $post->ID, '_ccg_event_instagram', true );

    // Tickets & program
    $ticket_price = get_post_meta( $post->ID, '_ccg_event_ticket_price', true );
    $booking_url  = get_post_meta( $post->ID, '_ccg_event_booking_url', true );
    $program_file = get_post_meta( $post->ID, '_ccg_event_program_file', true ); // attachment ID
    $program_text = get_post_meta( $post->ID, '_ccg_event_program_text', true );
    $languages    = ccg_events_csv_to_array( get_post_meta( $post->ID, '_ccg_event_languages', true ) );
    $audiences    = ccg_events_csv_to_array( get_post_meta( $post->ID, '_ccg_event_target_audience', true ) );

    // Outdoor / indoor
    $environment  = get_post_meta( $post->ID, '_ccg_event_environment', true );

    // Media
    $gallery      = get_post_meta( $post->ID, '_ccg_event_gallery', true );
    $gallery_ids  = $gallery ? array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) : [];
    $video_url    = get_post_meta( $post->ID, '_ccg_event_video_url', true );

    $recurrence_options = [
        ''               => __( 'Fără recurență', 'ccg-events' ),
        'none'           => __( 'Fără recurență', 'ccg-events' ),
        'daily'          => __( 'Zilnic', 'ccg-events' ),
        'weekly'         => __( 'Săptămânal', 'ccg-events' ),
        'monthly'        => __( 'Lunar', 'ccg-events' ),
        'yearly'         => __( 'Anual', 'ccg-events' ),
        'special-pattern'=> __( 'Model special (descris manual)', 'ccg-events' ),
    ];

    $language_options = [
        'RO' => 'RO',
        'RU' => 'RU',
        'EN' => 'EN',
    ];

    $audience_options = [
        'families'   => __( 'Familii', 'ccg-events' ),
        'couples'    => __( 'Cupluri', 'ccg-events' ),
        'children'   => __( 'Copii', 'ccg-events' ),
        'seniors'    => __( 'Seniori', 'ccg-events' ),
        'athletes'   => __( 'Sportivi', 'ccg-events' ),
        'tasters'    => __( 'Degustători', 'ccg-events' ),
        'foreigners' => __( 'Turiști străini', 'ccg-events' ),
        'photographers' => __( 'Fotografi', 'ccg-events' ),
        'locals'     => __( 'Comunități locale', 'ccg-events' ),
    ];

    $environment_options = [
        ''         => __( 'Nespecificat', 'ccg-events' ),
        'indoor'   => __( 'Indoor', 'ccg-events' ),
        'outdoor'  => __( 'Outdoor', 'ccg-events' ),
        'mixed'    => __( 'Indoor & Outdoor', 'ccg-events' ),
    ];

    wp_nonce_field( 'ccg_events_meta_save', 'ccg_events_meta_nonce' );
    ?>

    <div class="ccg-meta-wrapper">

        <!-- General Info -->
        <div class="ccg-field-group">
            <label for="ccg_event_short_description"><strong><?php esc_html_e( 'Descriere scurtă', 'ccg-events' ); ?></strong></label>
            <textarea id="ccg_event_short_description"
                      name="ccg_event_short_description"
                      class="widefat"
                      rows="3"
                      placeholder="<?php esc_attr_e( '1–2 fraze pentru carduri și listări.', 'ccg-events' ); ?>"><?php echo esc_textarea( $short_desc ); ?></textarea>
        </div>

        <!-- Event Dates -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Date eveniment', 'ccg-events' ); ?></strong></label>
            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_date_start"><?php esc_html_e( 'Data început', 'ccg-events' ); ?></label>
                    <input type="date"
                           id="ccg_event_date_start"
                           name="ccg_event_date_start"
                           value="<?php echo esc_attr( $date_start ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_time_start"><?php esc_html_e( 'Ora început', 'ccg-events' ); ?></label>
                    <input type="time"
                           id="ccg_event_time_start"
                           name="ccg_event_time_start"
                           value="<?php echo esc_attr( $time_start ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols ccg-event-end-wrapper">
                <div>
                    <label for="ccg_event_date_end"><?php esc_html_e( 'Data sfârșit', 'ccg-events' ); ?></label>
                    <input type="date"
                           id="ccg_event_date_end"
                           name="ccg_event_date_end"
                           value="<?php echo esc_attr( $date_end ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_time_end"><?php esc_html_e( 'Ora sfârșit', 'ccg-events' ); ?></label>
                    <input type="time"
                           id="ccg_event_time_end"
                           name="ccg_event_time_end"
                           value="<?php echo esc_attr( $time_end ); ?>"
                           class="widefat">
                </div>
            </div>

            <label>
                <input type="checkbox"
                       id="ccg_event_is_one_day"
                       name="ccg_event_is_one_day"
                       value="1" <?php checked( $is_one_day, '1' ); ?>>
                <?php esc_html_e( 'Eveniment de o singură zi', 'ccg-events' ); ?>
            </label>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_recurrence"><strong><?php esc_html_e( 'Recurență', 'ccg-events' ); ?></strong></label>
                    <select id="ccg_event_recurrence" name="ccg_event_recurrence" class="widefat">
                        <?php foreach ( $recurrence_options as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $recurrence, $value ); ?>>
                                <?php echo esc_html( $label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="ccg_event_recurrence_pattern"><strong><?php esc_html_e( 'Model recurență specială', 'ccg-events' ); ?></strong></label>
                    <input type="text"
                           id="ccg_event_recurrence_pattern"
                           name="ccg_event_recurrence_pattern"
                           value="<?php echo esc_attr( $rec_pattern ); ?>"
                           class="widefat"
                           placeholder="<?php esc_attr_e( 'ex: a doua sâmbătă din octombrie', 'ccg-events' ); ?>">
                </div>
            </div>
        </div>

        <?php  ccg_events_render_map_block($post); ?>
        <!-- Location -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Locație', 'ccg-events' ); ?></strong></label>
            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_address"><?php esc_html_e( 'Adresă completă', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_address"
                           name="ccg_event_address"
                           value="<?php echo esc_attr( $address ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_locality"><?php esc_html_e( 'Localitate (sat/oraș)', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_locality"
                           name="ccg_event_locality"
                           value="<?php echo esc_attr( $locality ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols ccg-meta-coords">
                <div>
                    <label for="ccg_event_lat"><?php esc_html_e( 'Latitudine', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_lat"
                           name="ccg_event_lat"
                           value="<?php echo esc_attr( $lat ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_lng"><?php esc_html_e( 'Longitudine', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_lng"
                           name="ccg_event_lng"
                           value="<?php echo esc_attr( $lng ); ?>"
                           class="widefat">
                </div>
            </div>
            <p class="description">
                <?php esc_html_e( 'Dacă evenimentul este asociat cu un Place, poți prelua coordonatele de acolo și doar să le ajustezi aici dacă este necesar.', 'ccg-events' ); ?>
            </p>
        </div>

        <!-- Related Place -->
        <div class="ccg-field-group">
            <label><strong>Loc asociat (Place)</strong></label>

            <input type="text"
                   id="ccg_event_place_search"
                   class="widefat"
                   placeholder="Caută o locație (Place)..." />

            <input type="hidden"
                   id="ccg_event_related_place"
                   name="ccg_event_related_place"
                   value="<?php echo esc_attr($related_place); ?>">

            <div id="ccg_event_place_results"></div>

        </div>

        <!-- Organizer -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Organizator', 'ccg-events' ); ?></strong></label>
            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_organizer_name"><?php esc_html_e( 'Nume organizator', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_organizer_name"
                           name="ccg_event_organizer_name"
                           value="<?php echo esc_attr( $org_name ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_organizer_website"><?php esc_html_e( 'Website organizator', 'ccg-events' ); ?></label>
                    <input type="url"
                           id="ccg_event_organizer_website"
                           name="ccg_event_organizer_website"
                           value="<?php echo esc_attr( $org_site ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_organizer_phone"><?php esc_html_e( 'Telefon', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_organizer_phone"
                           name="ccg_event_organizer_phone"
                           value="<?php echo esc_attr( $org_phone ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_organizer_email"><?php esc_html_e( 'Email', 'ccg-events' ); ?></label>
                    <input type="email"
                           id="ccg_event_organizer_email"
                           name="ccg_event_organizer_email"
                           value="<?php echo esc_attr( $org_email ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_facebook"><?php esc_html_e( 'Facebook Event Link', 'ccg-events' ); ?></label>
                    <input type="url"
                           id="ccg_event_facebook"
                           name="ccg_event_facebook"
                           value="<?php echo esc_attr( $fb_event ); ?>"
                           class="widefat">
                </div>
                <div>
                    <label for="ccg_event_instagram"><?php esc_html_e( 'Instagram Link', 'ccg-events' ); ?></label>
                    <input type="url"
                           id="ccg_event_instagram"
                           name="ccg_event_instagram"
                           value="<?php echo esc_attr( $ig_event ); ?>"
                           class="widefat">
                </div>
            </div>
        </div>

        <!-- Tickets & Program -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Bilete & Program', 'ccg-events' ); ?></strong></label>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_ticket_price"><?php esc_html_e( 'Preț bilet / acces', 'ccg-events' ); ?></label>
                    <input type="text"
                           id="ccg_event_ticket_price"
                           name="ccg_event_ticket_price"
                           value="<?php echo esc_attr( $ticket_price ); ?>"
                           class="widefat"
                           placeholder="<?php esc_attr_e( 'ex: Gratuit / 100 MDL / În funcție de pachet', 'ccg-events' ); ?>">
                </div>
                <div>
                    <label for="ccg_event_booking_url"><?php esc_html_e( 'Link rezervări (booking)', 'ccg-events' ); ?></label>
                    <input type="url"
                           id="ccg_event_booking_url"
                           name="ccg_event_booking_url"
                           value="<?php echo esc_attr( $booking_url ); ?>"
                           class="widefat">
                </div>
            </div>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_program_file"><?php esc_html_e( 'Fișier program (PDF, etc.)', 'ccg-events' ); ?></label>
                    <div class="ccg-program-file-wrapper">
                        <input type="hidden"
                               id="ccg_event_program_file"
                               name="ccg_event_program_file"
                               value="<?php echo esc_attr( $program_file ); ?>">
                        <button type="button" class="button" id="ccg_event_program_file_button">
                            <?php esc_html_e( 'Selectează fișier', 'ccg-events' ); ?>
                        </button>
                        <button type="button" class="button" id="ccg_event_program_file_clear" style="color:red;">
                            <?php esc_html_e( 'Șterge fișierul', 'ccg-events' ); ?>
                        </button>
                        <div id="ccg_event_program_file_label" class="ccg-program-file-label">
                            <?php
                            if ( $program_file ) {
                                $file = get_post( (int) $program_file );
                                if ( $file ) {
                                    echo esc_html( $file->post_title );
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="ccg_event_program_text"><?php esc_html_e( 'Detalii program (text)', 'ccg-events' ); ?></label>
                    <textarea id="ccg_event_program_text"
                              name="ccg_event_program_text"
                              class="widefat"
                              rows="4"><?php echo esc_textarea( $program_text ); ?></textarea>
                </div>
            </div>

            <div class="ccg-meta-two-cols">
                <div>
                    <label for="ccg_event_environment"><strong><?php esc_html_e( 'Tip locație', 'ccg-events' ); ?></strong></label>
                    <select id="ccg_event_environment" name="ccg_event_environment" class="widefat">
                        <?php foreach ( $environment_options as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $environment, $value ); ?>>
                                <?php echo esc_html( $label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Language & Audience -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Limbă eveniment', 'ccg-events' ); ?></strong></label>
            <div class="ccg-checkbox-grid">
                <?php foreach ( $language_options as $value => $label ) : ?>
                    <label>
                        <input type="checkbox"
                               name="ccg_event_languages[]"
                               value="<?php echo esc_attr( $value ); ?>"
                            <?php checked( in_array( $value, $languages, true ) ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Public țintă', 'ccg-events' ); ?></strong></label>
            <div class="ccg-checkbox-grid">
                <?php foreach ( $audience_options as $value => $label ) : ?>
                    <label>
                        <input type="checkbox"
                               name="ccg_event_target_audience[]"
                               value="<?php echo esc_attr( $value ); ?>"
                            <?php checked( in_array( $value, $audiences, true ) ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Media -->
        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Galerie imagini', 'ccg-events' ); ?></strong></label>

            <div class="ccg-gallery-grid" id="ccg_event_gallery_preview">
                <?php
                if ( ! empty( $gallery_ids ) ) {
                    foreach ( $gallery_ids as $img_id ) {
                        echo wp_get_attachment_image( $img_id, [ 80, 80 ] );
                    }
                }
                ?>
            </div>

            <input type="hidden" id="ccg_event_gallery" name="ccg_event_gallery" value="<?php echo esc_attr( $gallery ); ?>">

            <button type="button" class="button" id="ccg_event_gallery_button">
                <?php esc_html_e( 'Selectează imagini', 'ccg-events' ); ?>
            </button>
            <button type="button" class="button" id="ccg_event_gallery_clear" style="color:red;">
                <?php esc_html_e( 'Șterge galeria', 'ccg-events' ); ?>
            </button>
        </div>

        <div class="ccg-field-group">
            <label for="ccg_event_video_url"><strong><?php esc_html_e( 'Link video (YouTube, Vimeo etc.)', 'ccg-events' ); ?></strong></label>
            <input type="url"
                   id="ccg_event_video_url"
                   name="ccg_event_video_url"
                   value="<?php echo esc_attr( $video_url ); ?>"
                   class="widefat">
        </div>

    </div>

    <?php
}

/**
 * Salvare meta.
 */
function ccg_events_save_metabox( $post_id ) {

    if ( ! isset( $_POST['ccg_events_meta_nonce'] ) ||
        ! wp_verify_nonce( $_POST['ccg_events_meta_nonce'], 'ccg_events_meta_save' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Short description
    if ( isset( $_POST['ccg_event_short_description'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_short_description',
            sanitize_textarea_field( $_POST['ccg_event_short_description'] )
        );
    }

    // Dates
    $date_fields = [
        'ccg_event_date_start' => '_ccg_event_date_start',
        'ccg_event_time_start' => '_ccg_event_time_start',
        'ccg_event_date_end'   => '_ccg_event_date_end',
        'ccg_event_time_end'   => '_ccg_event_time_end',
    ];
    foreach ( $date_fields as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta(
                $post_id,
                $meta_key,
                sanitize_text_field( $_POST[ $field ] )
            );
        }
    }

    // One-day
    $is_one_day = isset( $_POST['ccg_event_is_one_day'] ) ? '1' : '0';
    update_post_meta( $post_id, '_ccg_event_is_one_day', $is_one_day );

    // Recurrence
    if ( isset( $_POST['ccg_event_recurrence'] ) ) {
        $rec = sanitize_text_field( $_POST['ccg_event_recurrence'] );
        update_post_meta( $post_id, '_ccg_event_recurrence', $rec );
    }
    if ( isset( $_POST['ccg_event_recurrence_pattern'] ) ) {
        $pat = sanitize_text_field( $_POST['ccg_event_recurrence_pattern'] );
        update_post_meta( $post_id, '_ccg_event_recurrence_pattern', $pat );
    }

    // Location
    $loc_fields = [
        'ccg_event_address'  => '_ccg_event_address',
        'ccg_event_locality' => '_ccg_event_locality',
        'ccg_event_lat'      => '_ccg_event_lat',
        'ccg_event_lng'      => '_ccg_event_lng',
    ];
    foreach ( $loc_fields as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta(
                $post_id,
                $meta_key,
                sanitize_text_field( $_POST[ $field ] )
            );
        }
    }

    if (isset($_POST['ccg_event_map_zoom'])) {
        update_post_meta($post_id, '_ccg_event_map_zoom', intval($_POST['ccg_event_map_zoom']));
    }

    // Related place
    if ( isset( $_POST['ccg_event_related_place'] ) ) {
        $pid = (int) $_POST['ccg_event_related_place'];
        update_post_meta( $post_id, '_ccg_event_related_place', $pid );
    }

    // Organizer
    $org_simple = [
        'ccg_event_organizer_name'   => '_ccg_event_organizer_name',
        'ccg_event_organizer_phone'  => '_ccg_event_organizer_phone',
    ];
    foreach ( $org_simple as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta(
                $post_id,
                $meta_key,
                sanitize_text_field( $_POST[ $field ] )
            );
        }
    }

    if ( isset( $_POST['ccg_event_organizer_website'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_organizer_website',
            esc_url_raw( $_POST['ccg_event_organizer_website'] )
        );
    }

    if ( isset( $_POST['ccg_event_organizer_email'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_organizer_email',
            sanitize_email( $_POST['ccg_event_organizer_email'] )
        );
    }

    if ( isset( $_POST['ccg_event_facebook'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_facebook',
            esc_url_raw( $_POST['ccg_event_facebook'] )
        );
    }

    if ( isset( $_POST['ccg_event_instagram'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_instagram',
            esc_url_raw( $_POST['ccg_event_instagram'] )
        );
    }

    // Tickets & program
    if ( isset( $_POST['ccg_event_ticket_price'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_ticket_price',
            sanitize_text_field( $_POST['ccg_event_ticket_price'] )
        );
    }
    if ( isset( $_POST['ccg_event_booking_url'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_booking_url',
            esc_url_raw( $_POST['ccg_event_booking_url'] )
        );
    }
    if ( isset( $_POST['ccg_event_program_file'] ) ) {
        $fid = (int) $_POST['ccg_event_program_file'];
        update_post_meta( $post_id, '_ccg_event_program_file', $fid );
    }
    if ( isset( $_POST['ccg_event_program_text'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_program_text',
            sanitize_textarea_field( $_POST['ccg_event_program_text'] )
        );
    }

    // Environment
    if ( isset( $_POST['ccg_event_environment'] ) ) {
        $env = sanitize_text_field( $_POST['ccg_event_environment'] );
        update_post_meta( $post_id, '_ccg_event_environment', $env );
    }

    // Languages (multi)
    if ( isset( $_POST['ccg_event_languages'] ) && is_array( $_POST['ccg_event_languages'] ) ) {
        $vals = array_map( 'sanitize_text_field', $_POST['ccg_event_languages'] );
        update_post_meta(
            $post_id,
            '_ccg_event_languages',
            ccg_events_array_to_csv( $vals )
        );
    } else {
        delete_post_meta( $post_id, '_ccg_event_languages' );
    }

    // Audiences (multi)
    if ( isset( $_POST['ccg_event_target_audience'] ) && is_array( $_POST['ccg_event_target_audience'] ) ) {
        $vals = array_map( 'sanitize_text_field', $_POST['ccg_event_target_audience'] );
        update_post_meta(
            $post_id,
            '_ccg_event_target_audience',
            ccg_events_array_to_csv( $vals )
        );
    } else {
        delete_post_meta( $post_id, '_ccg_event_target_audience' );
    }

    // Gallery
    if ( isset( $_POST['ccg_event_gallery'] ) ) {
        $raw = sanitize_text_field( $_POST['ccg_event_gallery'] );
        $ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
        $csv = implode( ',', $ids );
        update_post_meta( $post_id, '_ccg_event_gallery', $csv );
    }

    // Video
    if ( isset( $_POST['ccg_event_video_url'] ) ) {
        update_post_meta(
            $post_id,
            '_ccg_event_video_url',
            esc_url_raw( $_POST['ccg_event_video_url'] )
        );
    }
}

