<?php
/**
 * Card Route (Rută turistică)
 *
 * Așteaptă în $args['route'] un array cu:
 * - 'permalink'
 * - 'title'
 * - 'start'
 * - 'end'
 * - 'distance'      (ex: "8 km")
 * - 'duration'      (ex: "3h 20m")
 * - 'difficulty'    (ex: "Ușoară", "Medie", "Dificilă")
 * - 'image_html'
 */

$route = $args['route'] ?? [];

$permalink   = $route['permalink']   ?? get_permalink();
$title       = $route['title']       ?? get_the_title();
$start       = $route['start']       ?? '';
$end         = $route['end']         ?? '';
$distance    = $route['distance']    ?? '';
$duration    = $route['duration']    ?? '';
$difficulty  = $route['difficulty']  ?? '';
$image_html  = $route['image_html']  ?? ( has_post_thumbnail()
    ? get_the_post_thumbnail( get_the_ID(), 'medium_large', [
        'class' => 'w-full h-48 object-cover',
    ] )
    : ''
);
?>

<article class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">

    <!-- IMAGE -->
    <?php if ( $image_html ) : ?>
        <a href="<?php echo esc_url( $permalink ); ?>" class="block">
            <?php echo $image_html; ?>
        </a>
    <?php endif; ?>

    <!-- CONTENT -->
    <div class="p-4 flex flex-col flex-1">

        <!-- TOP INFO -->
        <div class="mb-3">
            <h3 class="text-base md:text-lg font-semibold text-slate-900 mb-1 line-clamp-2">
                <a href="<?php echo esc_url( $permalink ); ?>" class="hover:text-ccg-primary">
                    <?php echo esc_html( $title ); ?>
                </a>
            </h3>

            <?php if ( $start || $end ) : ?>
                <div class="text-xs text-slate-500 flex items-center gap-1">
                    <span class="font-medium"><?php echo esc_html( $start ); ?></span>
                    <span class="text-slate-400">→</span>
                    <span class="font-medium"><?php echo esc_html( $end ); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- BADGES -->
        <div class="flex flex-wrap gap-1.5 mb-4">

            <?php if ( $distance ) : ?>
                <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 px-2.5 py-0.5
                    text-[11px] font-medium text-slate-700">
                    🛣️ <?php echo esc_html( $distance ); ?>
                </span>
            <?php endif; ?>

            <?php if ( $duration ) : ?>
                <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 px-2.5 py-0.5
                    text-[11px] font-medium text-slate-700">
                    ⏱️ <?php echo esc_html( $duration ); ?>
                </span>
            <?php endif; ?>

            <?php if ( $difficulty ) : ?>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                    <?php echo $difficulty === 'Dificilă' ? 'bg-red-50 text-red-600 border border-red-200' : ''; ?>
                    <?php echo $difficulty === 'Medie'    ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : ''; ?>
                    <?php echo $difficulty === 'Ușoară'   ? 'bg-green-50 text-green-600 border border-green-200' : ''; ?>
                ">
                    <?php echo esc_html( $difficulty ); ?>
                </span>
            <?php endif; ?>

        </div>

        <!-- BUTTON -->
        <div class="mt-auto pt-2">
            <a href="<?php echo esc_url( $permalink ); ?>"
               class="inline-flex items-center text-xs font-semibold text-ccg-primary hover:text-ccg-primaryDark">
                Detalii rută →
            </a>
        </div>
    </div>

</article>
