<?php get_header(); ?>

<div class="relative min-h-screen bg-gradient-to-br from-[#ab2b36] via-[#3c2240] to-[#0e1a2b]">

    <!-- noruri luminoase -->
    <div class="absolute inset-0 mix-blend-screen pointer-events-none opacity-40">
        <div class="absolute top-10 left-10 w-96 h-96 bg-ccg-primary/30 blur-[140px]"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 bg-sky-500/20 blur-[160px]"></div>
    </div>

    <!-- conținutul paginii -->
    <div class="relative z-10">
        <!-- LOOP CARDURI AICI -->
        <section class="py-10">
            <div class="container mx-auto px-4">
                <h1 class="text-3xl font-bold text-white mb-2">Locații turistice</h1>
                <p class="text-white mb-8">
                    Explorează locurile autentice din Moldova.
                </p>

                <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();
                            get_template_part('template-parts/places/card-place-future');
                        endwhile;
                    else:
                        echo '<p class="text-slate-600">Nicio locație găsită.</p>';
                    endif;
                    ?>
                </div>

                <!-- PAGINATION -->
                <div class="mt-10">
                    <?php the_posts_pagination([
                            'mid_size' => 2,
                            'prev_text' => '«',
                            'next_text' => '»'
                    ]); ?>
                </div>
            </div>
        </section>
    </div>

</div>




<?php get_footer(); ?>
