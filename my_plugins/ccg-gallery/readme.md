# CCG Gallery

Plugin WordPress pentru calatoriicugust.com

## Funcționalități

- Detectează imaginile din `the_content` pe paginile single-* și le convertește în elemente de galerie (data-ccg-gallery).
- Lightbox full-screen, cu:
    - click pe imagine
    - navigare stânga/dreapta
    - ESC pentru închidere
    - săgeți stânga/dreapta de la tastatură
    - swipe pe mobil
    - counter `1 / N`
- Integrare cu plugin-ul `ccg-places`:
    - citește meta `_ccg_place_gallery` (CSV de ID-uri de imagini)
    - randează grid de imagini cu lightbox.

## Utilizare

1. Copiază folderul `ccg-gallery` în `wp-content/plugins/`.
2. Activează pluginul din **Plugins** în WordPress Admin.
3. Pe paginile single-* cu imagini în conținut, lightbox-ul se activează automat.
4. Pentru Places:
    - Asigură-te că meta `_ccg_place_gallery` este setat (CSV de ID-uri).
    - În template-ul single-place.php poți chema:

   ```php
   if ( function_exists( 'ccg_gallery_output_place_gallery' ) ) {
       ccg_gallery_output_place_gallery( get_the_ID(), 3 );
   }
