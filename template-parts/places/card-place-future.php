<?php
/**
 * Card Futuristic pentru Place — FULL GLASS, FULL CLICK
 */

$post_id = $args['post_id'] ?? get_the_ID();

// Meta
$short_desc = get_post_meta($post_id, '_ccg_place_short_desc', true);
$duration   = get_post_meta($post_id, '_ccg_place_visit_duration', true);
$access     = get_post_meta($post_id, '_ccg_place_access', true);
$price      = get_post_meta($post_id, '_ccg_place_price_range', true);

// Access multiple
$access_values = array_filter(array_map('trim', explode(',', (string)$access)));

$duration_options = ccg_get_place_duration_options();
$access_options   = ccg_get_place_access_options();
$price_options    = ccg_get_place_price_options();

// Iconițe
$duration_icons = [
        '30min'     => '⏱️',
        '1h'        => '⏱️',
        '2h'        => '⏱️',
        '3h'        => '⏱️',
        'half_day'  => '🕒',
        'full_day'  => '🕓',
];

$access_icons = [
        'car'   => '🚗',
        'bus'   => '🚌',
        'train' => '🚆',
        'boat'  => '⛵',
        'bike'  => '🚴‍♂️',
        'walk'  => '🚶‍♂️',
];

$price_icons = [
        'free'   => '<svg width="88" height="16" viewBox="0 0 88 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <!-- Bară 1 – activă -->
                          <rect x="0" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <!-- Bare inactive -->
                          <rect x="22" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                          <rect x="44" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                          <rect x="66" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                        </svg>
',
        'paid'    => '<svg width="88" height="16" viewBox="0 0 88 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <!-- Bare active -->
                      <rect x="0" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                      <rect x="22" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                      <!-- Bare inactive -->
                      <rect x="44" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                      <rect x="66" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                    </svg>
                    ',
        'moderate' => '<svg width="88" height="16" viewBox="0 0 88 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <!-- Bare active -->
                          <rect x="0" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <rect x="22" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <rect x="44" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <!-- Bară inactivă -->
                          <rect x="66" y="3" width="16" height="10" rx="3" fill="#E5E7EB" />
                        </svg>
                        ',
        'premium'   => '<svg width="88" height="16" viewBox="0 0 88 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <rect x="0"  y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <rect x="22" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <rect x="44" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                          <rect x="66" y="3" width="16" height="10" rx="3" fill="rgb(171,43,54)" />
                        </svg>
                        ',
];

$region_terms = wp_get_post_terms($post_id, 'place_region');
$region_name  = !empty($region_terms) ? $region_terms[0]->name : null;

$short_desc = get_post_meta($post_id, '_ccg_place_short_description', true);

$thumb = get_the_post_thumbnail_url($post_id, 'large');
$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
?>

<a href="<?php echo esc_url($permalink); ?>"
   class="block group cursor-pointer">

    <article
            class="relative overflow-hidden rounded-3xl transition-all duration-500
           shadow-xl hover:shadow-2xl hover:scale-[1.02]">

        <!-- STICLĂ GROASĂ -->
        <div class="absolute inset-0 rounded-3xl
                bg-white/15 backdrop-blur-2xl border border-white/20
                shadow-[0_8px_40px_rgba(0,0,0,0.4)]
                before:absolute before:inset-0 before:rounded-3xl
                before:bg-white/5">
        </div>

        <!-- NEON GLOW -->
        <div class="absolute inset-0 rounded-3xl pointer-events-none
                opacity-0 group-hover:opacity-100 transition duration-500"
             style="
            box-shadow:
                0 0 20px rgba(171,43,54,0.45),
                0 0 40px rgba(171,43,54,0.28),
                0 0 80px rgba(171,43,54,0.15);
         ">
        </div>

        <!-- LUCiU / GLARE -->
        <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0
                group-hover:opacity-60 transition duration-700
                bg-gradient-to-br from-white/30 via-white/10 to-transparent">
        </div>

        <!-- REFLEXIE COLȚ -->
        <div class="absolute top-0 left-0 w-40 h-40 rounded-tl-3xl pointer-events-none
                bg-gradient-to-br from-white/40 to-transparent opacity-40 blur-2xl">
        </div>

        <!-- IMAGINEA -->
        <div class="relative h-52 overflow-hidden rounded-b-[10%] z-[2]">
            <?php if ($thumb): ?>
                <img
                        src="<?php echo esc_url($thumb); ?>"
                        alt="<?php echo esc_attr($title); ?>"
                        class="w-full h-full object-cover transition duration-[1800ms]
                       group-hover:scale-110 group-hover:rotate-[1deg]"
                />
            <?php endif; ?>

            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/20 to-transparent"></div>
        </div>

        <!-- CONȚINUT -->
        <div class="relative z-[3] p-6">

            <!-- BADGE -->
            <div class="flex justify-between -mx-4 " >
                <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                   bg-ccg-primary/20 text-white mb-4 backdrop-blur-lg
                   border border-ccg-primary/30">
                <i class="mr-2"> Regiune: </i><?php echo esc_html($region_name); ?>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                   bg-ccg-primary/20 text-white mb-4 backdrop-blur-lg
                   border border-ccg-primary/30">
                    <!-- Price -->
                    <?php if ($price && isset($price_options[$price])): ?>

                        <span class="flex items-center gap-1">
                                <i class="mr-1">Preț:</i>
                                <span><?= $price_icons[$price]  ?></span>
                        </span>
                    <?php endif; ?>
                </span>
            </div>

            <!-- TITLU -->
            <h2 class="text-3xl font-bold text-white text-center drop-shadow-md mb-2">
                <?php echo esc_html($title); ?>
            </h2>


            <!-- SHORT DESCRIPTION -->
            <?php if ($short_desc): ?>
                <p class="text-slate-300 text-sm leading-relaxed my-4 text-center">
                    <?php echo esc_html(wp_trim_words($short_desc, 20)); ?>
                </p>
            <?php endif; ?>

            <div class="border-b -mx-6 py-2 mb-2">
                <!-- META INFO -->
                <div class="flex flex-wrap justify-center gap-3 mb-2 text-slate-200 text-sm">
                    <!-- Duration -->
                    <?php if ($duration && isset($duration_options[$duration])): ?>
                        <span class="flex items-center gap-1">
                            <i>Durata vizitei: </i>
                            <span><?= esc_html($duration_options[$duration]) ?></span>
                        </span>
                    <?php endif; ?>

                </div>
                <?php if (!empty($access_values)) : ?>
                    <div class="flex flex-wrap items-center gap-3 text-sm justify-center text-slate-200">
                        <i>Acces: </i>
                        <?php foreach ($access_values as $a_key): ?>
                            <?php if (isset($access_options[$a_key])): ?>
                                <span class="flex items-center gap-1">
                                <span><?= $access_icons[$a_key] ?? '🚗' ?></span>
                                <span><?= esc_html($access_options[$a_key]) ?></span>
                            </span>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>
            </div>



            <!-- CTA (nu mai trebuie buton separat; cardul e FULL CLICK) -->
            <div class="text-white font-semibold text-sm flex justify-end pt-2">
                Descoperă →
            </div>

        </div>

    </article>

</a>
