<?php
/**
 * Front-end hooks pentru CCG Gallery.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Inițializare front-end (hook-uri globale).
 */
function ccg_gallery_front_init() {
    add_filter( 'the_content', 'ccg_gallery_filter_the_content', 20 );
    add_action( 'wp_enqueue_scripts', 'ccg_gallery_enqueue_assets' );
    add_action( 'wp_footer', 'ccg_gallery_render_lightbox_container' );
}

/**
 * Verifică dacă pagina curentă are nevoie de asset-urile galeriei.
 *
 * @param WP_Post|int|null $post Post.
 * @return bool
 */
function ccg_gallery_post_needs_assets( $post = null ) {
    if ( ! is_singular() ) {
        return false;
    }

    $post = get_post( $post );

    if ( ! $post ) {
        return false;
    }

    $content = $post->post_content;

    $has_images = ( false !== strpos( $content, '<img ' ) );

    // Integrare minimală cu ccg-places: dacă există meta _ccg_place_gallery.
    $place_gallery_meta = get_post_meta( $post->ID, '_ccg_place_gallery', true );
    $has_place_gallery  = ! empty( $place_gallery_meta );

    return ( $has_images || $has_place_gallery );
}

/**
 * Enqueue CSS & JS doar când e nevoie.
 */
function ccg_gallery_enqueue_assets() {
    if ( ! ccg_gallery_post_needs_assets() ) {
        return;
    }

    wp_enqueue_style(
        'ccg-gallery',
        CCG_GALLERY_PLUGIN_URL . 'assets/css/ccg-gallery.css',
        array(),
        CCG_GALLERY_VERSION
    );

    wp_enqueue_script(
        'ccg-gallery',
        CCG_GALLERY_PLUGIN_URL . 'assets/js/ccg-gallery.js',
        array(),
        CCG_GALLERY_VERSION,
        true
    );
}

/**
 * Filter pe the_content – învelește imaginile în elemente cu data-ccg-gallery.
 *
 * Reguli:
 * - doar pe single-* (post, page, place etc.)
 * - nu rulează în admin.
 * - nu strică linkurile deja existente (doar adaugă atribute data-*)
 */
function ccg_gallery_filter_the_content( $content ) {
    if ( is_admin() ) {
        return $content;
    }

    if ( ! is_singular() ) {
        return $content;
    }

    if ( false === strpos( $content, '<img' ) ) {
        return $content;
    }

    global $post;

    if ( ! $post instanceof WP_Post ) {
        return $content;
    }

    $group = 'post-' . $post->ID;

    // Folosim DOMDocument pentru a nu rupe HTML-ul.
    if ( ! class_exists( 'DOMDocument' ) ) {
        // Fallback: nu modificăm conținutul dacă DOM nu este disponibil.
        return $content;
    }

    $libxml_previous_state = libxml_use_internal_errors( true );

    $dom      = new DOMDocument();
    $encoding = '<?xml encoding="utf-8" ?>';

    // Împachetăm conținutul într-un div, ca să putem extrage apoi doar interiorul.
    $html = '<div id="ccg-gallery-wrapper">' . $content . '</div>';

    // Pentru compatibilitate cu versiuni diferite de PHP/libxml nu folosim flags exotice.
    $dom->loadHTML( $encoding . $html );

    $xpath  = new DOMXPath( $dom );
    $images = $xpath->query( '//img' );

    $index = 0;

    /** @var DOMElement $img */
    foreach ( $images as $img ) {
        $parent = $img->parentNode;

        if ( ! $parent instanceof DOMElement ) {
            continue;
        }

        $src = $img->getAttribute( 'src' );

        if ( empty( $src ) ) {
            continue;
        }

        $alt = $img->getAttribute( 'alt' );

        // Caption din data-caption / title / alt.
        $caption = $img->getAttribute( 'data-caption' );

        if ( empty( $caption ) ) {
            $caption = $img->getAttribute( 'title' );
        }
        if ( empty( $caption ) ) {
            $caption = $alt;
        }

        // Dacă părintele este deja <a> sau <button>, doar adăugăm atributele data-*
        if ( in_array( strtolower( $parent->nodeName ), array( 'a', 'button' ), true ) ) {
            $parent->setAttribute( 'data-ccg-gallery', $group );
            $parent->setAttribute( 'data-ccg-gallery-index', (string) $index );
            $parent->setAttribute( 'data-ccg-gallery-src', $src );

            if ( ! empty( $caption ) ) {
                $parent->setAttribute( 'data-ccg-gallery-caption', $caption );
            }
        } else {
            // Altfel, creăm un <a> în jurul imaginii.
            $link = $dom->createElement( 'a' );
            $link->setAttribute( 'href', $src );
            $link->setAttribute( 'data-ccg-gallery', $group );
            $link->setAttribute( 'data-ccg-gallery-index', (string) $index );
            $link->setAttribute( 'data-ccg-gallery-src', $src );

            if ( ! empty( $caption ) ) {
                $link->setAttribute( 'data-ccg-gallery-caption', $caption );
            }

            // Păstrăm eventualele clase de pe imagine și adăugăm una de plugin.
            $existing_class = $img->getAttribute( 'class' );
            $link_class     = trim( $existing_class . ' ccg-gallery-image-link' );

            if ( ! empty( $link_class ) ) {
                $link->setAttribute( 'class', $link_class );
            }

            $parent->replaceChild( $link, $img );
            $link->appendChild( $img );
        }

        $index++;
    }

    // Extragem doar conținutul div-ului nostru wrapper.
    $wrapper = $dom->getElementById( 'ccg-gallery-wrapper' );

    if ( ! $wrapper ) {
        libxml_clear_errors();
        libxml_use_internal_errors( $libxml_previous_state );
        return $content;
    }

    $new_content = '';

    foreach ( $wrapper->childNodes as $child ) {
        $new_content .= $dom->saveHTML( $child );
    }

    libxml_clear_errors();
    libxml_use_internal_errors( $libxml_previous_state );

    return $new_content;
}

/**
 * Randează containerul global de lightbox în footer.
 */
function ccg_gallery_render_lightbox_container() {
    if ( ! is_singular() ) {
        return;
    }

    // Poate fi extins cu filtre dacă este nevoie.
    ?>
    <div class="ccg-lightbox" data-ccg-lightbox hidden aria-hidden="true">
        <div class="ccg-lightbox-backdrop" data-ccg-lightbox-close></div>

        <div class="ccg-lightbox-inner" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Galerie imagini', 'ccg-gallery' ); ?>">
            <button class="ccg-lightbox-close" type="button" data-ccg-lightbox-close aria-label="<?php echo esc_attr__( 'Închide', 'ccg-gallery' ); ?>">
                <svg
                    class="ccg-icon ccg-icon-close"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>

            <button class="ccg-lightbox-nav ccg-lightbox-prev" type="button" data-ccg-lightbox-prev aria-label="<?php echo esc_attr__( 'Imaginea anterioară', 'ccg-gallery' ); ?>">
                <svg
                    class="ccg-icon ccg-icon-prev"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <polyline
                        points="15 6 9 12 15 18"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </button>

            <button class="ccg-lightbox-nav ccg-lightbox-next" type="button" data-ccg-lightbox-next aria-label="<?php echo esc_attr__( 'Imaginea următoare', 'ccg-gallery' ); ?>">
                <svg
                    class="ccg-icon ccg-icon-next"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <polyline
                        points="9 6 15 12 9 18"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </button>

            <figure class="ccg-lightbox-figure">
                <img class="ccg-lightbox-image" data-ccg-lightbox-image src="" alt="" loading="lazy" />
                <figcaption class="ccg-lightbox-caption" data-ccg-lightbox-caption></figcaption>
                <div class="ccg-lightbox-counter" data-ccg-lightbox-counter></div>
            </figure>
        </div>
    </div>
    <?php
}
