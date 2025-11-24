<?php
/**
 * Template: Gallery
 *
 * Variables available:
 * - $images       (array)
 * - $post_id      (int)
 * - $gallery_args (array)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( empty( $images ) || empty( $post_id ) ) {
    return;
}

$gallery_id = 'post-' . (int) $post_id;
?>
    <div class="ccg-gallery" data-gallery="<?php echo esc_attr( $gallery_id ); ?>">
        <?php foreach ( $images as $image ) : ?>
            <?php
            $full_url = isset( $image['url'] ) ? $image['url'] : '';
            $alt      = isset( $image['alt'] ) ? $image['alt'] : '';
            $caption  = isset( $image['caption'] ) ? $image['caption'] : '';
            ?>
            <a href="<?php echo esc_url( $full_url ); ?>"
               class="ccg-gallery__item"
               data-lightbox="<?php echo esc_attr( $gallery_id ); ?>"
               data-caption="<?php echo esc_attr( $caption ); ?>">
                <img src="<?php echo esc_url( $full_url ); ?>"
                     alt="<?php echo esc_attr( $alt ); ?>" />
            </a>
        <?php endforeach; ?>
    </div>

<?php
// Include global lightbox overlay container (once per page is enough – JS poate avea grijă de asta).
$lightbox_template = ccg_gallery_get_template_path( 'lightbox.php' );
if ( $lightbox_template ) {
    include $lightbox_template;
}
