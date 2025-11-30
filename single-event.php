<?php
/**
 * Single Event template
 */

get_header();

while ( have_posts() ) :
    the_post();

    $post_id = get_the_ID();

    $short_desc  = get_post_meta( $post_id, '_ccg_event_short_description', true );
    $long_desc   = get_post_meta( $post_id, '_ccg_event_long_description', true );
    $address     = get_post_meta( $post_id, '_ccg_event_address', true );
    $locality    = ccg_events_get_locality( $post_id );
    $region_name = ccg_events_get_region_name( $post_id );
    $date_label  = ccg_events_format_date_range( $post_id, true );

    $ticket_price = get_post_meta( $post_id, '_ccg_event_ticket_price', true );
    $booking_url  = get_post_meta( $post_id, '_ccg_event_booking_url', true );

    $organizer_name   = get_post_meta( $post_id, '_ccg_event_organizer_name', true );
    $organizer_web    = get_post_meta( $post_id, '_ccg_event_organizer_website', true );
    $organizer_phone  = get_post_meta( $post_id, '_ccg_event_organizer_phone', true );
    $organizer_email  = get_post_meta( $post_id, '_ccg_event_organizer_email', true );
    $facebook_event   = get_post_meta( $post_id, '_ccg_event_facebook_event', true );
    $instagram_event  = get_post_meta( $post_id, '_ccg_event_instagram_event', true );

    $program_file     = get_post_meta( $post_id, '_ccg_event_program_file', true );
    $program_details  = get_post_meta( $post_id, '_ccg_event_program_details', true );
    $video_url        = get_post_meta( $post_id, '_ccg_event_video_url', true );

    $lat  = get_post_meta( $post_id, '_ccg_event_lat', true );
    $lng  = get_post_meta( $post_id, '_ccg_event_lng', true );
    $zoom = get_post_meta( $post_id, '_ccg_event_map_zoom', true );

    $related_place_id = (int) get_post_meta( $post_id, '_ccg_event_related_place', true );

    $primary_type = ccg_events_get_primary_type( $post_id );
    $themes       = ccg_events_get_themes_list( $post_id );
    $languages    = (array) get_post_meta( $post_id, '_ccg_event_language', true );
    $audience     = (array) get_post_meta( $post_id, '_ccg_event_target_audience', true );
    ?>

    <main class="bg-slate-50 pb-16">
        <!-- HERO -->
        <section class="bg-white border-b border-slate-200">
            <div class="container mx-auto px-4 py-8 md:py-10 grid gap-8 md:grid-cols-3 items-center">

                <div class="md:col-span-2">
                    <?php if ( $primary_type ) : ?>
                        <span class="inline-flex items-center px-3 py-1 mb-3 rounded-full bg-ccg-primary/10 text-ccg-primary text-xs font-semibold">
                            <?php echo esc_html( $primary_type->name ); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-3">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ( $date_label ) : ?>
                        <div class="text-slate-700 mb-2 flex items-center gap-2">
                            <span>📅</span>
                            <span><?php echo esc_html( $date_label ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $locality || $region_name ) : ?>
                        <div class="text-slate-600 mb-4 flex items-center gap-2">
                            <span>📍</span>
                            <span>
                            <?php
                            $location_parts = array_filter( [ $locality, $region_name ] );
                            echo esc_html( implode( ', ', $location_parts ) );
                            ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $short_desc ) : ?>
                        <p class="text-slate-600 max-w-xl mb-4">
                            <?php echo esc_html( $short_desc ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( $booking_url ) : ?>
                        <a href="<?php echo esc_url( $booking_url ); ?>"
                           class="inline-flex items-center px-5 py-2.5 rounded-xl bg-ccg-primary text-white text-sm font-semibold hover:bg-ccg-primaryDark transition"
                           target="_blank" rel="noopener">
                            Rezervă / Cumpără bilete
                        </a>
                    <?php endif; ?>
                </div>

                <!-- HERO IMAGE -->
                <div class="md:col-span-1">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="rounded-2xl overflow-hidden shadow-md">
                            <?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-full object-cover' ] ); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>

        <div class="container mx-auto px-4 mt-10 grid gap-10 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">

            <!-- LEFT COLUMN -->
            <div class="space-y-8">

                <!-- GALLERY -->
                <?php if ( function_exists( 'ccg_gallery_render' ) ) : ?>
                    <section>
                        <?php ccg_gallery_render( get_the_ID() ); ?>
                    </section>
                <?php endif; ?>

                <!-- LONG DESCRIPTION -->
                <section class="prose max-w-none prose-slate">
                    <?php
                    if ( $long_desc ) {
                        echo wp_kses_post( wpautop( $long_desc ) );
                    } else {
                        the_content();
                    }
                    ?>
                </section>

                <!-- PROGRAM DETAILS -->
                <?php if ( $program_file || $program_details ) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
                        <h2 class="text-lg font-bold text-slate-900">Programul evenimentului</h2>

                        <?php if ( $program_file ) : ?>
                            <a href="<?php echo esc_url( wp_get_attachment_url( $program_file ) ); ?>"
                               class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition"
                               target="_blank" rel="noopener">
                                Descarcă programul (PDF)
                            </a>
                        <?php endif; ?>

                        <?php if ( $program_details ) : ?>
                            <div class="prose max-w-none prose-slate">
                                <?php echo wp_kses_post( wpautop( $program_details ) ); ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <!-- VIDEO -->
                <?php if ( $video_url ) : ?>
                    <section class="space-y-3">
                        <h2 class="text-lg font-bold text-slate-900">Video</h2>
                        <div class="aspect-video rounded-2xl overflow-hidden shadow-sm bg-black">
                            <?php
                            // YouTube / Vimeo embed
                            if ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
                                echo wp_oembed_get( $video_url );
                            } elseif ( strpos( $video_url, 'vimeo.com' ) !== false ) {
                                echo wp_oembed_get( $video_url );
                            } else {
                                // Direct video file
                                ?>
                                <video src="<?php echo esc_url( $video_url ); ?>" controls class="w-full h-full"></video>
                                <?php
                            }
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

            </div>

            <!-- RIGHT COLUMN: DETAILS + ORGANIZER + MAP + RELATED PLACE -->
            <aside class="space-y-8">

                <!-- EVENT DETAILS GRID -->
                <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Detalii eveniment</h2>
                    <dl class="space-y-2 text-sm text-slate-700">

                        <?php
                        $date_start = get_post_meta( $post_id, '_ccg_event_date_start', true );
                        $time_start = get_post_meta( $post_id, '_ccg_event_time_start', true );
                        $date_end   = get_post_meta( $post_id, '_ccg_event_date_end', true );
                        $time_end   = get_post_meta( $post_id, '_ccg_event_time_end', true );
                        ?>

                        <?php if ( $date_start ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Data început</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( ccg_events_format_date_range( $post_id, false ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $time_start ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Ora început</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( substr( $time_start, 0, 5 ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $date_end ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Data final</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( ccg_events_format_date_range( $post_id, false ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $time_end ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Ora final</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( substr( $time_end, 0, 5 ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $address ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Adresă</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( $address ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $locality ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Localitate</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( $locality ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $primary_type ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Tip eveniment</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( $primary_type->name ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $themes ) ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Teme</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( implode( ', ', $themes ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $audience ) ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Public țintă</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( implode( ', ', (array) $audience ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $languages ) ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Limbi</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( implode( ', ', (array) $languages ) ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ( $ticket_price ) : ?>
                            <div class="flex justify-between gap-4">
                                <dt class="font-semibold text-slate-500">Preț bilete</dt>
                                <dd class="text-right">
                                    <?php echo esc_html( $ticket_price ); ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                    </dl>
                </section>

                <!-- ORGANIZER -->
                <?php if ( $organizer_name || $organizer_phone || $organizer_email || $organizer_web || $facebook_event || $instagram_event ) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Organizator</h2>
                        <div class="space-y-2 text-sm text-slate-700">
                            <?php if ( $organizer_name ) : ?>
                                <div>
                                    <span class="font-semibold text-slate-500">Nume:</span>
                                    <span class="ml-1"><?php echo esc_html( $organizer_name ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $organizer_phone ) : ?>
                                <div>
                                    <span class="font-semibold text-slate-500">Telefon:</span>
                                    <a href="tel:<?php echo esc_attr( $organizer_phone ); ?>" class="ml-1 text-ccg-primary hover:underline">
                                        <?php echo esc_html( $organizer_phone ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ( $organizer_email ) : ?>
                                <div>
                                    <span class="font-semibold text-slate-500">Email:</span>
                                    <a href="mailto:<?php echo esc_attr( $organizer_email ); ?>" class="ml-1 text-ccg-primary hover:underline">
                                        <?php echo esc_html( $organizer_email ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ( $organizer_web ) : ?>
                                <div>
                                    <span class="font-semibold text-slate-500">Website:</span>
                                    <a href="<?php echo esc_url( $organizer_web ); ?>" target="_blank" rel="noopener"
                                       class="ml-1 text-ccg-primary hover:underline">
                                        Vizitează site-ul
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="flex gap-3 pt-2">
                                <?php if ( $facebook_event ) : ?>
                                    <a href="<?php echo esc_url( $facebook_event ); ?>" target="_blank" rel="noopener"
                                       class="text-slate-600 hover:text-ccg-primary text-sm">
                                        Facebook
                                    </a>
                                <?php endif; ?>
                                <?php if ( $instagram_event ) : ?>
                                    <a href="<?php echo esc_url( $instagram_event ); ?>" target="_blank" rel="noopener"
                                       class="text-slate-600 hover:text-ccg-primary text-sm">
                                        Instagram
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- MAPA -->
                <?php if ( $lat && $lng ) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-0 overflow-hidden">
                        <h2 class="text-lg font-bold text-slate-900 px-5 pt-5">Locație pe hartă</h2>
                        <div
                                id="ccg-event-map"
                                class="mt-4 w-full h-[320px]"
                                data-lat="<?php echo esc_attr( $lat ); ?>"
                                data-lng="<?php echo esc_attr( $lng ); ?>"
                                data-zoom="<?php echo esc_attr( $zoom ?: 12 ); ?>"
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                        ></div>
                    </section>
                <?php endif; ?>

                <!-- RELATED PLACE -->
                <?php if ( $related_place_id && get_post_status( $related_place_id ) === 'publish' ) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Locația evenimentului</h2>
                        <?php
                        // Ajustează path-ul la card-place după structura temei tale
                        get_template_part(
                                'template-parts/places/card',
                                'place',
                                [ 'post_id' => $related_place_id ]
                        );
                        ?>
                        <div class="mt-3">
                            <a href="<?php echo get_permalink( $related_place_id ); ?>"
                               class="text-sm font-semibold text-ccg-primary hover:underline">
                                Vezi locația →
                            </a>
                        </div>
                    </section>
                <?php endif; ?>

            </aside>

        </div>
    </main>

<?php
endwhile;

get_footer();
