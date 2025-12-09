<?php
/**
 * Card Futuristic pentru Eveniment (Event)
 *
 * Așteaptă $args['post_id'] sau folosește global $post.
 */

$post_id = $args['post_id'] ?? get_the_ID();

$short_desc   = get_post_meta( $post_id, '_ccg_event_short_description', true );
$date_label   = ccg_events_format_date_range( $post_id, true );
$locality     = ccg_events_get_locality( $post_id );
$region       = ccg_events_get_region_name( $post_id );
$primary_type = ccg_events_get_primary_type( $post_id );
$themes       = ccg_events_get_themes_list( $post_id );

$title     = get_the_title( $post_id );
$permalink = get_permalink( $post_id );
$thumb     = get_the_post_thumbnail_url( $post_id, 'large' );

// pentru afișarea locatiei combinate
$location_parts = array_filter( [ $locality, $region ] );
?>

<a href="<?php echo esc_url( $permalink ); ?>" class="block group cursor-pointer">

    <article
        class="relative overflow-hidden rounded-3xl transition-all duration-500
           shadow-xl hover:shadow-2xl hover:scale-[1.02]">

        <!-- STICLĂ GROASĂ -->
        <div class="absolute inset-0 rounded-3xl
                bg-white/12 backdrop-blur-2xl border border-white/20
                shadow-[0_8px_40px_rgba(0,0,0,0.45)]
                before:absolute before:inset-0 before:rounded-3xl
                before:bg-white/5">
        </div>

        <!-- NEON GLOW ALBASTRU (EVENT) -->
        <div class="absolute inset-0 rounded-3xl pointer-events-none
                opacity-0 group-hover:opacity-100 transition duration-500"
             style="
            box-shadow:
                0 0 20px rgba(62,193,255,0.55),
                0 0 40px rgba(62,193,255,0.30),
                0 0 80px rgba(62,193,255,0.18);
         ">
        </div>

        <!-- LUCiU / GLARE -->
        <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0
                group-hover:opacity-60 transition duration-700
                bg-gradient-to-br from-white/35 via-white/10 to-transparent">
        </div>

        <!-- REFLEXIE COLȚ -->
        <div class="absolute top-0 left-0 w-40 h-40 rounded-tl-3xl pointer-events-none
                bg-gradient-to-br from-white/40 to-transparent opacity-40 blur-2xl">
        </div>

        <!-- IMAGINE -->
        <div class="relative h-52 overflow-hidden rounded-b-[10%] z-[2]">
            <?php if ( $thumb ) : ?>
                <img
                    src="<?php echo esc_url( $thumb ); ?>"
                    alt="<?php echo esc_attr( $title ); ?>"
                    class="w-full h-full object-cover transition duration-[1800ms]
                       group-hover:scale-110 group-hover:rotate-[1deg]"
                />
            <?php endif; ?>

            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/20 to-transparent"></div>
        </div>

        <!-- CONȚINUT -->
        <div class="relative z-[3] p-6">

            <!-- EVENT TYPE -->
            <?php if ( $primary_type ) : ?>
                <span class="inline-flex items-center px-3 py-1 mb-4 rounded-full text-xs font-semibold
                         bg-cyan-400/20 text-cyan-200 backdrop-blur border border-cyan-300/40">
                🎉 <?php echo esc_html( $primary_type->name ); ?>
            </span>
            <?php endif; ?>

            <!-- TITLU -->
            <h2 class="text-3xl text-center font-bold text-white drop-shadow-md mb-2">
                <?php echo esc_html( $title ); ?>
            </h2>

            <!-- DATE -->
            <?php if ( $date_label ) : ?>
                <div class="flex items-center gap-2 text-sm text-cyan-100 mb-2">
                    <span>📅</span>
                    <span><?php echo esc_html( $date_label ); ?></span>
                </div>
            <?php endif; ?>

            <!-- LOCATION (localitate + regiune) -->
            <?php if ( ! empty( $location_parts ) ) : ?>
                <div class="flex items-center gap-2 text-sm text-slate-200 mb-3">
                    <span class="text-cyan-300">📍</span>
                    <span><?php echo esc_html( implode( ', ', $location_parts ) ); ?></span>
                </div>
            <?php endif; ?>

            <!-- SHORT DESCRIPTION -->
            <?php if ( $short_desc ) : ?>
                <p class="text-slate-200 text-center text-sm leading-relaxed mb-4">
                    <?php echo esc_html( $short_desc ); ?>
                </p>
            <?php else : ?>
                <p class="text-slate-200 text-sm leading-relaxed mb-4">
                    <?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 20 ) ); ?>
                </p>
            <?php endif; ?>

            <!-- THEMES -->
            <?php if ( ! empty( $themes ) ) : ?>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <?php foreach ( $themes as $theme_name ) : ?>
                        <span class="inline-flex px-2 py-0.5 rounded-full bg-slate-900/40
                                 text-slate-100 text-[11px] border border-slate-500/40">
                        <?php echo esc_html( $theme_name ); ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- CTA (cardul este deja FULL CLICK) -->
            <div class="text-cyan-300 font-semibold text-sm">
                Detalii eveniment →
            </div>

        </div>

    </article>

</a>
