<?php
/**
 * Card Place (Loc turistic)
 *
 * Așteaptă (opțional) în $args['place'] un array cu:
 * - 'permalink'
 * - 'title'
 * - 'region'        (ex: "Orheiul Vechi, raionul Orhei")
 * - 'tags'          (array de stringuri: [ 'Natură', 'Gastronomie' ])
 * - 'image_html'    (HTML pentru imagine; poate fi the_post_thumbnail)
 * - 'meta'          (text scurt: ex. "4.8 · 32 recenzii" sau altceva)
 */

$place = $args['place'] ?? [];

// fallback: folosim post-ul curent dacă nu primim $args
$permalink  = $place['permalink']  ?? get_permalink();
$title      = $place['title']      ?? get_the_title();
$region     = $place['region']     ?? '';
$tags       = $place['tags']       ?? [];
$image_html = $place['image_html'] ?? ( has_post_thumbnail()
    ? get_the_post_thumbnail( get_the_ID(), 'medium_large', [
        'class' => 'w-full h-48 object-cover',
    ] )
    : ''
);
$meta       = $place['meta']       ?? '';
?>

<article class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">
    <?php if ( $image_html ) : ?>
        <a href="<?php echo esc_url( $permalink ); ?>" class="block">
            <?php echo $image_html; ?>
        </a>
    <?php endif; ?>

    <div class="p-4 flex flex-col flex-1">
        <?php if ( $region ) : ?>
            <div class="text-xs text-slate-400 mb-1 line-clamp-1">
                <?php echo esc_html( $region ); ?>
            </div>
        <?php endif; ?>

        <h3 class="text-base md:text-lg font-semibold text-slate-900 mb-2 line-clamp-2">
            <a href="<?php echo esc_url( $permalink ); ?>" class="hover:text-ccg-primary">
                <?php echo esc_html( $title ); ?>
            </a>
        </h3>

        <?php if ( $meta ) : ?>
            <div class="text-xs text-slate-500 mb-3">
                <?php echo esc_html( $meta ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $tags ) && is_array( $tags ) ) : ?>
            <div class="flex flex-wrap gap-1.5 mb-3">
                <?php foreach ( $tags as $tag ) : ?>
                    <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 px-2.5 py-0.5 text-[11px] font-medium text-slate-600">
                        <?php echo esc_html( $tag ); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-auto pt-2">
            <a href="<?php echo esc_url( $permalink ); ?>"
               class="inline-flex items-center text-xs font-semibold text-ccg-primary hover:text-ccg-primaryDark">
                Vezi detalii →
            </a>
        </div>
    </div>
</article>
