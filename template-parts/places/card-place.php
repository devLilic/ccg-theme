<?php
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
$duration = get_post_meta($post_id, '_ccg_place_visit_duration', true);
$price    = get_post_meta($post_id, '_ccg_place_price_range', true);
$access   = get_post_meta($post_id, '_ccg_place_access', true);
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
            <?php if ($price): ?>
                <span class="flex items-center gap-1">
                    💰 <?php echo esc_html($price); ?>
                </span>
            <?php endif; ?>

            <?php if ($duration): ?>
                <span class="flex items-center gap-1">
                    ⏱ <?php echo esc_html($duration); ?>
                </span>
            <?php endif; ?>

            <?php if ($access): ?>
                <span class="flex items-center gap-1">
                    🚗
                </span>
            <?php endif; ?>
        </div>

        <span class="inline-flex items-center text-ccg-primary font-semibold text-sm">
            Vezi detalii →
        </span>
    </div>
</a>
