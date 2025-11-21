<?php
$partners = new WP_Query([
        'post_type' => 'partners',
        'posts_per_page' => -1,
        'meta_key' => '_ccg_published',
        'meta_value' => '1'
]);

if (!$partners->have_posts()) return;
?>

<section class="py-10 bg-white border-t border-slate-200">
    <div class="w-full mx-auto ">
        <div class="container mx-auto px-4">

            <h2 class="text-2xl font-bold text-slate-800 mb-6">
                <span class="inline-block w-2 h-5 bg-ccg-primary rounded-full"></span>
                Sponsori &amp; Parteneri</h2>
        </div>

        <!-- OUTER WRAPPER -->
        <div class="relative overflow-hidden">

            <!-- TRACK (duplicat pentru efect infinit real) -->
            <div class="flex gap-5 ccg-marquee-track whitespace-nowrap">

                <?php while ($partners->have_posts()) : $partners->the_post(); ?>
                    <?php get_template_part('template-parts/partners/partner-card'); ?>
                <?php endwhile; ?>

                <?php
                // duplicăm conținutul pentru scroll infinit
                $partners->rewind_posts();
                while ($partners->have_posts()) : $partners->the_post(); ?>
                    <?php get_template_part('template-parts/partners/partner-card'); ?>
                <?php endwhile; ?>
                <?php
                // duplicăm conținutul pentru scroll infinit
                $partners->rewind_posts();
                while ($partners->have_posts()) : $partners->the_post(); ?>
                    <?php get_template_part('template-parts/partners/partner-card'); ?>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</section>

<?php wp_reset_postdata(); ?>
