<?php get_header(); ?>

<?php
$short = get_post_meta(get_the_ID(), '_ccg_place_short_description', true);
$lat = get_post_meta(get_the_ID(), '_ccg_place_lat', true);
$lng = get_post_meta(get_the_ID(), '_ccg_place_lng', true);

$opening = get_post_meta(get_the_ID(), '_ccg_place_opening_hours', true);
$duration = get_post_meta(get_the_ID(), '_ccg_place_visit_duration', true);
$season = get_post_meta(get_the_ID(), '_ccg_place_best_season', true);
$recommended = explode(',', get_post_meta(get_the_ID(), '_ccg_place_recommended_for', true));
$access = explode(',', get_post_meta(get_the_ID(), '_ccg_place_access', true));
$price = get_post_meta(get_the_ID(), '_ccg_place_price_range', true);

$website = get_post_meta(get_the_ID(), '_ccg_place_contact_website', true);
$phone = get_post_meta(get_the_ID(), '_ccg_place_contact_phone', true);
$email = get_post_meta(get_the_ID(), '_ccg_place_contact_email', true);
$booking = get_post_meta(get_the_ID(), '_ccg_place_booking_url', true);

$gallery_raw = get_post_meta(get_the_ID(), '_ccg_place_gallery', true);
$gallery_ids = $gallery_raw ? explode(',', $gallery_raw) : [];
?>

<!-- HERO -->
<?php if ($gallery_ids): ?>
    <div class="w-full h-80 md:h-96 overflow-hidden relative">
        <?php echo wp_get_attachment_image($gallery_ids[0], 'full', false, [
                'class' => 'w-full h-full object-cover'
        ]); ?>
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute container px-4 bottom-6 left-1/2 -translate-x-1/2 text-white">
            <h1 class="text-4xl font-bold"><?php the_title(); ?></h1>
            <?php if ($short): ?>
                <p class="max-w-xl mt-2"><?php echo esc_html($short); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<section class="py-10">
    <div class="container mx-auto px-4">

        <!-- CONTENT -->
        <div class="prose max-w-none mb-10">
            <?php the_content(); ?>
        </div>

        <!-- DETAILS GRID -->
        <div class="grid md:grid-cols-2 gap-8">

            <!-- LEFT -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Informații utile</h2>
                <ul class="space-y-2 text-slate-700">

                    <?php if ($duration): ?>
                        <li><strong>Durată vizită:</strong> <?php echo esc_html($duration); ?></li>
                    <?php endif; ?>

                    <?php if ($season): ?>
                        <li><strong>Sezon recomandat:</strong> <?php echo esc_html($season); ?></li>
                    <?php endif; ?>

                    <?php if ($price): ?>
                        <li><strong>Cost:</strong> <?php echo esc_html($price); ?></li>
                    <?php endif; ?>

                    <?php if (!empty($access)): ?>
                        <li><strong>Acces:</strong> <?php echo implode(', ', array_map('esc_html', $access)); ?></li>
                    <?php endif; ?>

                    <?php if ($opening): ?>
                        <li><strong>Orar vizitare:</strong><br><?php echo wp_kses_post(wpautop($opening)); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- CONTACT -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Contact & Rezervări</h2>
                <ul class="space-y-2 text-slate-700">
                    <?php if ($website): ?>
                        <li><strong>Website:</strong> <a href="<?php echo esc_url($website); ?>" class="text-ccg-primary"><?php echo esc_html($website); ?></a></li>
                    <?php endif; ?>

                    <?php if ($phone): ?>
                        <li><strong>Telefon:</strong> <?php echo esc_html($phone); ?></li>
                    <?php endif; ?>

                    <?php if ($email): ?>
                        <li><strong>Email:</strong> <?php echo esc_html($email); ?></li>
                    <?php endif; ?>

                    <?php if ($booking): ?>
                        <li><a href="<?php echo esc_url($booking); ?>" class="inline-block mt-2 px-4 py-2 bg-ccg-primary text-white rounded shadow">Rezervă vizita</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- MAP -->
        <?php if ($lat && $lng): ?>
            <div id="ccg-place-map" class="w-full xl:w-1/2 mx-auto h-96 mt-10 rounded-xl shadow"></div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const map = L.map('ccg-place-map').setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 14);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(map);
                    L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map);
                });
            </script>
        <?php endif; ?>

        <!-- RELAȚII (viitor) -->
        <?php get_template_part('template-parts/places/related-events'); ?>
        <?php get_template_part('template-parts/places/related-routes'); ?>

    </div>
</section>

<?php get_footer(); ?>
