<?php
/**
 * Single Place template
 * CPT: place
 */

get_header();

if (!have_posts()) {
    get_footer();
    exit;
}

the_post();

$post_id = get_the_ID();

/**
 * META
 */
$short_desc = get_post_meta($post_id, '_ccg_place_short_description', true);
$lat = get_post_meta($post_id, '_ccg_place_lat', true);
$lng = get_post_meta($post_id, '_ccg_place_lng', true);
$gallery_raw = get_post_meta($post_id, '_ccg_place_gallery', true);
$opening_hours = get_post_meta($post_id, '_ccg_place_opening_hours', true);

$visit_duration = get_post_meta($post_id, '_ccg_place_visit_duration', true);
$best_season = get_post_meta($post_id, '_ccg_place_best_season', true);
$recommended_for_raw = get_post_meta($post_id, '_ccg_place_recommended_for', true);
$access_raw = get_post_meta($post_id, '_ccg_place_access', true);
$price_range = get_post_meta($post_id, '_ccg_place_price_range', true);

// Contact
$contact_website = get_post_meta($post_id, '_ccg_place_contact_website', true);
$contact_phone = get_post_meta($post_id, '_ccg_place_contact_phone', true);
$contact_email = get_post_meta($post_id, '_ccg_place_contact_email', true);
$contact_social = get_post_meta($post_id, '_ccg_place_contact_social', true);
$booking_url = get_post_meta($post_id, '_ccg_place_booking_url', true);

// Relații
$related_events = get_post_meta($post_id, '_ccg_place_related_events', true);
$related_routes = get_post_meta($post_id, '_ccg_place_related_routes', true);
$related_wineries = get_post_meta($post_id, '_ccg_place_related_wineries', true);

/**
 * TAXONOMII
 */
$regions = get_the_terms($post_id, 'place_region');
$categories = get_the_terms($post_id, 'place_category');
$tourism_zone = get_the_terms($post_id, 'tourism_zone');
$themes = get_the_terms($post_id, 'place_theme');

$region_label = $regions && !is_wp_error($regions) ? $regions[0]->name : null;
$category_label = $categories && !is_wp_error($categories) ? $categories[0]->name : null;

/**
 * Helpers pentru label-uri
 */
$duration_labels = [
        '30m' => '30 min',
        '1h' => '1 oră',
        'half_day' => 'Jumătate de zi',
        'full_day' => 'O zi întreagă',
        '2_plus' => '2+ zile',
];

$season_labels = [
        'all_year' => 'Tot anul',
        'spring' => 'Primăvara',
        'summer' => 'Vara',
        'autumn' => 'Toamna',
        'winter' => 'Iarna',
];

$price_labels = [
        '' => 'Nespecificat',
        'free' => 'Gratuit',
        'paid' => 'Contra cost',
        'moderate' => 'Preț moderat',
        'premium' => 'Premium',
];

$recommended_labels = [
        'families' => 'Familii',
        'hikers' => 'Drumeți',
        'food_lovers' => 'Gurmanzi',
        'photographers' => 'Fotografi',
        'pilgrims' => 'Pelerini',
        'cyclists' => 'Cicliști',
        'nature_lovers' => 'Iubitori de natură',
        'adventure' => 'Aventură',
];

$access_labels = [
        'car' => 'Auto',
        'bus' => 'Autobuz',
        'train' => 'Tren',
        'boat' => 'Barcă',
        'bike' => 'Bicicletă',
];

// CSV → array
$recommended_for = $recommended_for_raw
        ? array_filter(array_map('trim', explode(',', $recommended_for_raw)))
        : [];
$access = $access_raw
        ? array_filter(array_map('trim', explode(',', $access_raw)))
        : [];

// Galerie
$gallery_ids = $gallery_raw
        ? array_filter(array_map('intval', explode(',', $gallery_raw)))
        : [];

// Imagine pentru hero (prima din galerie sau featured)
$hero_img_html = '';
if (!empty($gallery_ids)) {
    $hero_img_html = wp_get_attachment_image(
            $gallery_ids[0],
            'large',
            false,
            ['class' => 'w-full h-full object-cover object-center']
    );
} elseif (has_post_thumbnail()) {
    $hero_img_html = get_the_post_thumbnail(
            $post_id,
            'large',
            ['class' => 'w-full h-full object-cover object-center']
    );
}
?>

<main class="bg-slate-50 min-h-screen">

    <!-- HERO -->
    <section class="relative bg-slate-900 min-h-[70vh] md:min-h-[75vh] lg:min-h-[80vh] overflow-hidden text-white">
        <?php if ($hero_img_html) : ?>
            <div class="absolute inset-0 overflow-hidden">
                <?php echo $hero_img_html; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-black/30"></div>
            </div>
        <?php endif; ?>

        <div class="relative z-10 container mx-auto px-4 py-10 md:py-16">
            <div class="max-w-3xl">

                <?php if ($category_label) : ?>
                    <span class="inline-block px-3 py-1 rounded-full bg-ccg-primary/80 text-xs font-semibold mb-3">
                        <?php echo esc_html($category_label); ?>
                    </span>
                <?php endif; ?>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-3">
                    <?php the_title(); ?>
                </h1>

                <?php if ($region_label) : ?>
                    <p class="text-sm md:text-base text-slate-100 mb-4">
                        📍 <?php echo esc_html($region_label); ?>
                    </p>
                <?php endif; ?>

                <?php if ($short_desc) : ?>
                    <p class="text-slate-100/90 mb-6">
                        <?php echo esc_html($short_desc); ?>
                    </p>
                <?php endif; ?>

                <div class="flex flex-wrap gap-3 text-sm">

                    <?php if ($price_range) : ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 border border-white/30">
                            💰 <?php echo esc_html($price_labels[$price_range] ?? $price_range); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($visit_duration) : ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 border border-white/30">
                            ⏱ <?php echo esc_html($duration_labels[$visit_duration] ?? $visit_duration); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($best_season) : ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 border border-white/30">
                            🌦 <?php echo esc_html($season_labels[$best_season] ?? $best_season); ?>
                        </span>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="py-10">
        <div class="container mx-auto px-4 grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">

            <!-- COL STÂNGA – conținut -->
            <div class="space-y-8">

                <!-- Descriere lungă -->
                <article class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4">Despre locație</h2>
                    <div class="prose prose-slate max-w-none">
                        <?php the_content(); ?>
                    </div>
                </article>

                <!-- Program & recomandări -->
                <?php if ($opening_hours || !empty($recommended_for) || !empty($access)) : ?>
                    <section class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">Informații pentru vizită</h2>

                        <?php if ($opening_hours) : ?>
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-1">
                                    Program de vizitare
                                </h3>
                                <div class="text-sm text-slate-700">
                                    <?php echo wp_kses_post(wpautop($opening_hours)); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($recommended_for)) : ?>
                            <div class="mb-4">
                                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-2">
                                    Recomandat pentru
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($recommended_for as $code) :
                                        $label = $recommended_labels[$code] ?? $code;
                                        ?>
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs">
                                            <?php echo esc_html($label); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($access)) : ?>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-2">
                                    Acces
                                </h3>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <?php foreach ($access as $code) :
                                        $label = $access_labels[$code] ?? $code;
                                        ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-slate-800">
                                            <?php echo esc_html($label); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </section>
                <?php endif; ?>

                <!-- Galerie -->
                <?php if (!empty($gallery_ids)) : ?>
                    <section class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">Galerie foto</h2>
                        <?php ccg_gallery_render(get_the_ID()); ?>
                    </section>
                <?php endif; ?>

                <!-- Relații: evenimente, rute, crame -->
                <?php
                // helper mic
                function ccg_render_related_posts($ids_csv, $post_type, $title)
                {
                    $ids = $ids_csv
                            ? array_filter(array_map('intval', explode(',', $ids_csv)))
                            : [];

                    if (empty($ids)) {
                        return;
                    }

                    $q = new WP_Query([
                            'post_type' => $post_type,
                            'post__in' => $ids,
                            'orderby' => 'post__in',
                            'posts_per_page' => -1,
                    ]);

                    if (!$q->have_posts()) {
                        return;
                    }

                    echo '<section class="bg-white rounded-xl shadow-sm p-6">';
                    echo '<h2 class="text-xl font-bold text-slate-800 mb-4">' . esc_html($title) . '</h2>';
                    echo '<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">';

                    while ($q->have_posts()) {
                        $q->the_post();
                        echo '<article class="border border-slate-200 rounded-lg p-4 hover:shadow-sm transition">';
                        echo '<h3 class="text-sm font-semibold mb-1">';
                        echo '<a href="' . esc_url(get_permalink()) . '" class="hover:text-ccg-primary">';
                        the_title();
                        echo '</a>';
                        echo '</h3>';
                        echo '<p class="text-xs text-slate-500 mb-1">' . esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? '') . '</p>';
                        echo '</article>';
                    }

                    echo '</div>';
                    echo '</section>';

                    wp_reset_postdata();
                }

                ccg_render_related_posts($related_events, 'event', 'Evenimente la această locație');
                ccg_render_related_posts($related_routes, 'route', 'Rute care trec pe aici');
                ccg_render_related_posts($related_wineries, 'winery', 'Crame & vinării asociate');
                ?>

            </div>

            <!-- COL DREAPTA – hartă & contact -->
            <aside class="space-y-6">

                <!-- Harta -->
                <?php if ($lat && $lng) : ?>
                    <section class="bg-white rounded-xl shadow-sm p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3">Localizare pe hartă</h2>
                        <div
                                id="ccg-place-map"
                                class="w-full h-64 rounded-lg border border-slate-200"
                                data-lat="<?php echo esc_attr($lat); ?>"
                                data-lng="<?php echo esc_attr($lng); ?>"
                        ></div>
                        <p class="text-xs text-slate-500 mt-2">
                            Harta este orientativă. Verifică accesul rutier înainte de plecare.
                        </p>
                    </section>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const map = L.map('ccg-place-map').setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 14);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap'
                            }).addTo(map);
                            L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map);
                        });
                    </script>
                <?php endif; ?>

                <!-- Info vizitare scurt -->
                <section class="bg-white rounded-xl shadow-sm p-4">
                    <h2 class="text-sm font-semibold text-slate-800 mb-3">Rezumat vizită</h2>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <?php if ($price_range) : ?>
                            <li>💰
                                <strong>Preț:</strong> <?php echo esc_html($price_labels[$price_range] ?? $price_range); ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($visit_duration) : ?>
                            <li>⏱ <strong>Durată
                                    recomandată:</strong> <?php echo esc_html($duration_labels[$visit_duration] ?? $visit_duration); ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($best_season) : ?>
                            <li>🌦 <strong>Sezon
                                    ideal:</strong> <?php echo esc_html($season_labels[$best_season] ?? $best_season); ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </section>

                <!-- Contact & booking -->
                <?php if ($contact_website || $contact_phone || $contact_email || $booking_url || $contact_social) : ?>
                    <section class="bg-white rounded-xl shadow-sm p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3">Contact & Rezervări</h2>
                        <ul class="space-y-2 text-sm text-slate-700">
                            <?php if ($contact_website) : ?>
                                <li>
                                    🌐 <a href="<?php echo esc_url($contact_website); ?>" target="_blank"
                                         class="text-ccg-primary hover:underline">
                                        Site oficial
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($contact_phone) : ?>
                                <li>📞 <a href="tel:<?php echo esc_attr($contact_phone); ?>" class="hover:underline">
                                        <?php echo esc_html($contact_phone); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($contact_email) : ?>
                                <li>✉️ <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="hover:underline">
                                        <?php echo esc_html($contact_email); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($booking_url) : ?>
                                <li>
                                    📝 <a href="<?php echo esc_url($booking_url); ?>" target="_blank"
                                         class="text-ccg-primary font-semibold hover:underline">
                                        Rezervă / programează o vizită →
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <?php if ($contact_social) : ?>
                            <div class="mt-3 text-xs text-slate-600">
                                <?php echo wp_kses_post(wpautop($contact_social)); ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

            </aside>

        </div>
    </section>

</main>

<?php get_footer(); ?>
