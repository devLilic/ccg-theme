<?php
/**
 * Template: Lightbox Overlay
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Evităm să înscriem de mai multe ori.
if ( did_action( 'ccg_gallery_lightbox_output' ) ) {
    return;
}
do_action( 'ccg_gallery_lightbox_output' );
?>
<div class="ccg-lightbox" id="ccg-lightbox" aria-hidden="true">
    <div class="ccg-lightbox__overlay"></div>
    <div class="ccg-lightbox__inner">
        <button class="ccg-lightbox__close" type="button" aria-label="Close">&times;</button>
        <button class="ccg-lightbox__prev" type="button" aria-label="Previous">&#10094;</button>
        <button class="ccg-lightbox__next" type="button" aria-label="Next">&#10095;</button>

        <div class="ccg-lightbox__content">
            <img src="" alt="" class="ccg-lightbox__image" />
            <div class="ccg-lightbox__caption"></div>
        </div>
    </div>
</div>
