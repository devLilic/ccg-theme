<?php
$short = get_post_meta(get_the_ID(), '_ccg_place_short_description', true);
$region = get_the_terms(get_the_ID(), 'place_region');
$category = get_the_terms(get_the_ID(), 'place_category');
$themes = get_the_terms(get_the_ID(), 'place_theme');

$gallery_raw = get_post_meta(get_the_ID(), '_ccg_place_gallery', true);
$gallery_ids = $gallery_raw ? explode(',', $gallery_raw) : [];
$image_id = $gallery_ids[0] ?? null;
?>

<a href="<?php the_permalink(); ?>"
   class="block rounded-xl overflow-hidden shadow hover:shadow-lg transition group bg-white">

    <!-- IMAGE -->
    <div class="relative w-full h-48 bg-slate-200">
        <?php if ($image_id): ?>
            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                    'class' => 'absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition'
            ]); ?>
        <?php endif; ?>
    </div>

    <!-- CONTENT -->
    <div class="p-4">
        <?php if ($region): ?>
            <span class="inline-block px-2 py-1 text-xs rounded bg-slate-100 text-slate-600 mb-2">
                <?php echo esc_html($region[0]->name); ?>
            </span>
        <?php endif; ?>

        <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-ccg-primary">
            <?php the_title(); ?>
        </h3>

        <?php if ($short): ?>
            <p class="text-slate-600 text-sm line-clamp-2 mb-3">
                <?php echo esc_html($short); ?>
            </p>
        <?php endif; ?>

        <!-- THEMES -->
        <?php if ($themes): ?>
            <div class="flex flex-wrap gap-1 mt-2">
                <?php foreach (array_slice($themes, 0, 3) as $t): ?>
                    <span class="px-2 py-0.5 text-xs rounded bg-slate-100 text-slate-600">
                        <?php echo esc_html($t->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</a>
