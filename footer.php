</main>
<?php get_template_part('template-parts/blocks/block', 'partners'); ?>
<footer class="bg-white border-t border-slate-200 mt-8">
    <div class="container mx-auto px-4 py-6 text-sm text-slate-500 flex flex-col md:flex-row items-center justify-between gap-3">
        <span>&copy; <?php echo date('Y'); ?> Calatorii Cu Gust</span>

        <nav>
            <?php
            wp_nav_menu( [
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'flex gap-4',
            ] );
            ?>
        </nav>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
