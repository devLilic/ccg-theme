<?php
/**
 * Card pentru Eveniment (Event)
 *
 * Așteaptă $args['post_id'] sau folosește global $post.
 */

$post_id = $args['post_id'] ?? get_the_ID();

$short_desc = get_post_meta( $post_id, '_ccg_event_short_description', true );
$date_label = ccg_events_format_date_range( $post_id, true );
$locality   = ccg_events_get_locality( $post_id );
$region     = ccg_events_get_region_name( $post_id );
$primary_type = ccg_events_get_primary_type( $post_id );

$themes = ccg_events_get_themes_list( $post_id );
?>

<article class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200 hover:shadow-md transition">

    <!-- IMAGE -->
    <?php if ( has_post_thumbnail( $post_id ) ) : ?>
        <a href="<?php echo get_permalink( $post_id ); ?>" class="block">
            <?php echo get_the_post_thumbnail( $post_id, 'medium_large', [
                    'class' => 'w-full h-48 object-cover'
            ] ); ?>
        </a>
    <?php endif; ?>

    <div class="p-5 flex flex-col h-full">

        <!-- EVENT TYPE -->
        <?php if ( $primary_type ) : ?>
            <?php var_dump($primary_type);?>
            <span class="inline-flex items-center px-3 py-1 mb-3 rounded-full bg-ccg-primary/10 text-ccg-primary text-xs font-semibold">
                <?php echo esc_html( $primary_type->name ); ?>
            </span>
        <?php endif; ?>

        <!-- TITLE -->
        <h2 class="text-lg font-bold text-slate-900 mb-2">
            <a href="<?php echo get_permalink( $post_id ); ?>" class="hover:text-ccg-primary transition">
                <?php echo esc_html( get_the_title( $post_id ) ); ?>
            </a>
        </h2>

        <!-- DATE -->
        <?php if ( $date_label ) : ?>
            <div class="text-sm text-slate-600 mb-2">
                📅 <?php echo esc_html( $date_label ); ?>
            </div>
        <?php endif; ?>

        <!-- LOCATION (localitate + regiune) -->
        <?php if ( $locality || $region ) : ?>
            <div class="text-sm text-slate-500 mb-3 flex items-center gap-1">
                📍
                <span>
                    <?php
                    $location_parts = array_filter( [ $locality, $region ] );
                    echo esc_html( implode( ', ', $location_parts ) );
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- SHORT DESCRIPTION -->
        <?php if ( $short_desc ) : ?>
            <p class="text-slate-600 text-sm mb-4 flex-1">
                <?php echo esc_html( $short_desc ); ?>
            </p>
        <?php else : ?>
            <p class="text-slate-600 text-sm mb-4 flex-1">
                <?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 20 ) ); ?>
            </p>
        <?php endif; ?>

        <!-- THEMES (optional) -->
        <?php if ( ! empty( $themes ) ) : ?>
            <div class="flex flex-wrap gap-1 mb-4">
                <?php foreach ( $themes as $theme_name ) : ?>
                    <span class="inline-flex px-2 py-0.5 rounded-full bg-slate-50 text-slate-500 text-xs">
                        <?php echo esc_html( $theme_name ); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- CTA -->
        <div class="mt-auto">
            <a
                    href="<?php echo get_permalink( $post_id ); ?>"
                    class="inline-flex items-center text-sm font-semibold text-ccg-primary hover:underline"
            >
                Detalii eveniment →
            </a>
        </div>

    </div>
</article>
