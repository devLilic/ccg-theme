<?php
// Iconițe pentru opțiuni
$duration_icons = [
        '30min' => '⏱️',
        '1h'    => '⏱️',
        '2h'    => '⏱️',
        '3h'    => '⏱️',
        'half_day' => '🕒',
        'full_day' => '🕓',
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
        'free' => '🆓',
        'paid'  => '💸',
        'moderate' => '💰',
        'premium' => '💎',
];

$post_id = get_the_ID();

// ----- Image -----
$gallery = get_post_meta($post_id, '_ccg_place_gallery', true);
$img_id = 0;
if ($gallery) {
    $ids = array_map('intval', explode(',', $gallery));
    $img_id = $ids[0] ?? 0;
}

// Fallback image
$image_html = $img_id
        ? wp_get_attachment_image($img_id, 'medium_large', false, [
                'class' => 'w-full h-48 object-cover rounded-t-xl'
        ])
        : '<div class="w-full h-48 bg-slate-200 rounded-t-xl flex items-center justify-center text-slate-500">Fără imagine</div>';

// ----- Region -----
$regions = get_the_terms($post_id, 'place_region');
$region_label = $regions ? $regions[0]->name : 'Nerolocat';

// ----- Category -----
$cats = get_the_terms($post_id, 'place_category');
$cat_label = $cats ? $cats[0]->name : false;

// ----- Description -----
$short = get_post_meta($post_id, '_ccg_place_short_description', true);

// ----- Meta small icons -----
//$duration = get_post_meta($post_id, '_ccg_place_visit_duration', true);
//$price    = get_post_meta($post_id, '_ccg_place_price_range', true);
//$access   = get_post_meta($post_id, '_ccg_place_access', true);

// Meta values
//$duration = get_post_meta($post_id, '_ccg_place_visit_duration', true);
//$access   = get_post_meta($post_id, '_ccg_place_access', true);
//$price    = get_post_meta($post_id, '_ccg_place_price_range', true);

// Importăm opțiunile din plugin (sunt globale)
$duration_options = ccg_get_place_duration_options();
$access_options   = ccg_get_place_access_options();
$price_options    = ccg_get_place_price_options();

$duration = get_post_meta($post_id, '_ccg_place_visit_duration', true);
$access_str   = get_post_meta($post_id, '_ccg_place_access', true);
$price    = get_post_meta($post_id, '_ccg_place_price_range', true);

$access_values = array_filter( array_map( 'trim', explode( ',', $access_str ) ) );

?>
<a href="<?php echo esc_url(get_permalink()); ?>"
   class="block bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

    <?php echo $image_html; ?>

    <div class="p-4">

        <?php if ($cat_label): ?>
            <span class="inline-block text-xs px-2 py-1 bg-ccg-primary/10 text-ccg-primary rounded-full mb-2">
                <?php echo esc_html($cat_label); ?>
            </span>
        <?php endif; ?>

        <h3 class="text-lg font-bold text-slate-800 leading-tight mb-1">
            <?php the_title(); ?>
        </h3>

        <span class="text-sm text-slate-500 block mb-3">
            <?php echo esc_html($region_label); ?>
        </span>

        <?php if ($short): ?>
            <p class="text-sm text-slate-600 mb-4 line-clamp-2">
                <?php echo esc_html($short); ?>
            </p>
        <?php endif; ?>

        <!-- Small meta icons row -->
        <div class="flex items-center gap-3 text-xs text-slate-600 mb-4">

            <?php if ($price && isset($price_options[$price])) : ?>
                <span class="flex items-center gap-1">
                    <span class="text-lg"><?= $price_icons[$price] ?? '💰' ?></span>
                    <span><?= esc_html($price_options[$price]) ?></span>
                </span>
            <?php endif; ?>

            <?php if ($duration && isset($duration_options[$duration])) : ?>
                <span class="flex items-center gap-1">
                    <span class="text-lg"><?= $duration_icons[$duration] ?? '⏱️' ?></span>
                    <span><?= esc_html($duration_options[$duration]) ?></span>
                </span>
            <?php endif; ?>

            <?php if (!empty($access_values)) : ?>
                <div class="flex items-center gap-3 flex-wrap">
                    <?php foreach ($access_values as $access_key) : ?>
                        <?php if (isset($access_options[$access_key])) : ?>
                            <span class="flex items-center gap-1 text-sm text-slate-600">
                    <span class="text-lg">
                        <?= $access_icons[$access_key] ?? '🚗' ?>
                    </span>
                    <span><?= esc_html($access_options[$access_key]) ?></span>
                </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>



        </div>

        <span class="inline-flex items-center text-ccg-primary font-semibold text-sm">
            Vezi detalii →
        </span>
    </div>
</a>
