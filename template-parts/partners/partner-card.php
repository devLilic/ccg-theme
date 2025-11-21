<?php
$banner_id = get_post_meta(get_the_ID(), '_ccg_banner_id', true);
$url       = get_post_meta(get_the_ID(), '_ccg_site_url', true);
$tier      = get_post_meta(get_the_ID(), '_ccg_partner_tier', true);

$bg_class = match ($tier) {
    'gold'   => 'bg-gradient-to-br from-yellow-200 via-yellow-300 to-yellow-400 border-yellow-500',
    'silver' => 'bg-gradient-to-br from-gray-200 via-gray-300 to-gray-400 border-gray-500',
    default  => 'bg-white border-slate-300',
};
?>

<a href="<?php echo esc_url($url ?: '#'); ?>"
   target="_blank"
   class="flex-none block w-28 h-28 md:w-44 md:h-44 group">

    <div class="w-full h-full flex items-center justify-center
                rounded-xl shadow-sm p-1 md:p-2 border <?php echo $bg_class; ?>">

        <?php echo wp_get_attachment_image(
            $banner_id,
            'ccg-partner',
            false,
            ['class' => 'max-w-full max-h-full object-contain rounded-xl']
        ); ?>

    </div>
</a>
