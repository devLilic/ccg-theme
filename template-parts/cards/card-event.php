<?php
/**
 * Card Event (Eveniment)
 *
 * Așteaptă (opțional) în $args['event'] un array cu:
 * - 'permalink'
 * - 'title'
 * - 'location'      (ex: "Chișinău, Piața Marii Adunări Naționale")
 * - 'date_label'    (ex: "12 octombrie 2025")
 * - 'date_day'      (ex: "12")
 * - 'date_month'    (ex: "OCT")
 * - 'price_label'   (ex: "Intrare liberă" sau "de la 150 MDL")
 * - 'image_html'    (HTML pentru imagine; opțional)
 */

$event = $args['event'] ?? [];

$permalink   = $event['permalink']   ?? get_permalink();
$title       = $event['title']       ?? get_the_title();
$location    = $event['location']    ?? '';
$date_label  = $event['date_label']  ?? '';
$date_day    = $event['date_day']    ?? '';
$date_month  = $event['date_month']  ?? '';
$price_label = $event['price_label'] ?? '';
$image_html  = $event['image_html']  ?? ( has_post_thumbnail()
    ? get_the_post_thumbnail( get_the_ID(), 'medium', [
        'class' => 'w-full h-40 object-cover',
    ] )
    : ''
);
?>

<article class="min-w-[260px] md:min-w-0 bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">
    <?php if ( $image_html ) : ?>
        <a href="<?php echo esc_url( $permalink ); ?>" class="block">
            <?php echo $image_html; ?>
        </a>
    <?php endif; ?>

    <div class="p-4 flex flex-col flex-1">
        <div class="flex items-start gap-3 mb-3">
            <?php if ( $date_day || $date_month ) : ?>
                <div class="flex flex-col items-center justify-center rounded-xl bg-ccg-primary/10 px-2.5 py-1.5 min-w-[52px]">
                    <?php if ( $date_day ) : ?>
                        <span class="text-base font-bold text-ccg-primary leading-none">
                            <?php echo esc_html( $date_day ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ( $date_month ) : ?>
                        <span class="text-[11px] font-semibold text-ccg-primary mt-0.5 uppercase tracking-[0.08em]">
                            <?php echo esc_html( $date_month ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="flex-1">
                <h3 class="text-sm md:text-base font-semibold text-slate-900 mb-1 line-clamp-2">
                    <a href="<?php echo esc_url( $permalink ); ?>" class="hover:text-ccg-primary">
                        <?php echo esc_html( $title ); ?>
                    </a>
                </h3>

                <?php if ( $location ) : ?>
                    <div class="text-xs text-slate-500 mb-1 line-clamp-1">
                        <?php echo esc_html( $location ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $date_label ) : ?>
                    <div class="text-[11px] text-slate-400">
                        <?php echo esc_html( $date_label ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $price_label ) : ?>
            <div class="mb-3">
                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                    <?php echo esc_html( $price_label ); ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="mt-auto pt-1">
            <a href="<?php echo esc_url( $permalink ); ?>"
               class="inline-flex items-center text-xs font-semibold text-ccg-primary hover:text-ccg-primaryDark">
                Detalii eveniment →
            </a>
        </div>
    </div>
</article>
