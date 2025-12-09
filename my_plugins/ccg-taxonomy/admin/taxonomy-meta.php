<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Metabox config taxonomie.
 */
function ccg_taxmgr_register_metabox() {
    add_meta_box(
        'ccg_taxmgr_box',
        __( 'Definiție Taxonomie', 'ccg-taxonomy' ),
        'ccg_taxmgr_render_metabox',
        'ccg_taxonomy',
        'normal',
        'high'
    );
}

/**
 * Render metabox.
 */
function ccg_taxmgr_render_metabox( $post ) {

    $slug         = get_post_meta( $post->ID, '_ccgtax_slug', true );
    $plural_label = get_post_meta( $post->ID, '_ccgtax_label_plural', true );
    $singular     = get_post_meta( $post->ID, '_ccgtax_label_singular', true );
    $menu_name    = get_post_meta( $post->ID, '_ccgtax_label_menu', true );
    $description  = get_post_meta( $post->ID, '_ccgtax_description', true );

    $public          = get_post_meta( $post->ID, '_ccgtax_public', true );
    $hierarchical    = get_post_meta( $post->ID, '_ccgtax_hierarchical', true );
    $show_admin_col  = get_post_meta( $post->ID, '_ccgtax_show_admin_column', true );
    $show_in_rest    = get_post_meta( $post->ID, '_ccgtax_show_in_rest', true );

    $post_types      = get_post_meta( $post->ID, '_ccgtax_post_types', true );
    $post_types_arr  = $post_types ? array_map( 'trim', explode( ',', $post_types ) ) : [];

    $available_pts   = ccg_taxmgr_get_available_post_types();

    wp_nonce_field( 'ccg_taxmgr_meta_save', 'ccg_taxmgr_meta_nonce' );
    ?>

    <style>
        .ccg-taxmgr-wrapper{margin-top:10px;}
        .ccg-field-group{margin-bottom:16px;}
        .ccg-field-group label{display:block;font-weight:600;margin-bottom:4px;}
        .ccg-meta-two-cols{display:flex;gap:12px;}
        .ccg-meta-two-cols>.ccg-field-group{flex:1;}
        .ccg-pt-list{max-height:220px;overflow-y:auto;padding:8px;border:1px solid #ddd;border-radius:4px;background:#fff;}
        .ccg-pt-list label{font-weight:400;}
        .ccg-pt-list input[type="checkbox"]{margin-right:4px;}
    </style>

    <div class="ccg-taxmgr-wrapper">

        <div class="ccg-field-group">
            <label for="ccgtax_slug"><strong><?php esc_html_e( 'Slug taxonomie', 'ccg-taxonomy' ); ?></strong></label>
            <input type="text"
                   id="ccgtax_slug"
                   name="ccgtax_slug"
                   class="widefat"
                   value="<?php echo esc_attr( $slug ); ?>"
                   placeholder="<?php esc_attr_e( 'Ex: region, tourism_zone, event_theme', 'ccg-taxonomy' ); ?>">
            <p class="description">
                <?php esc_html_e( 'Folosit în URL și în cod (register_taxonomy("slug", ...)). Dacă este gol, se generează din titlu.', 'ccg-taxonomy' ); ?>
            </p>
        </div>

        <div class="ccg-meta-two-cols">
            <div class="ccg-field-group">
                <label for="ccgtax_label_plural"><strong><?php esc_html_e( 'Label plural (name)', 'ccg-taxonomy' ); ?></strong></label>
                <input type="text"
                       id="ccgtax_label_plural"
                       name="ccgtax_label_plural"
                       class="widefat"
                       value="<?php echo esc_attr( $plural_label ); ?>"
                       placeholder="<?php esc_attr_e( 'Ex: Regiuni', 'ccg-taxonomy' ); ?>">
            </div>

            <div class="ccg-field-group">
                <label for="ccgtax_label_singular"><strong><?php esc_html_e( 'Label singular (singular_name)', 'ccg-taxonomy' ); ?></strong></label>
                <input type="text"
                       id="ccgtax_label_singular"
                       name="ccgtax_label_singular"
                       class="widefat"
                       value="<?php echo esc_attr( $singular ); ?>"
                       placeholder="<?php esc_attr_e( 'Ex: Regiune', 'ccg-taxonomy' ); ?>">
            </div>
        </div>

        <div class="ccg-field-group">
            <label for="ccgtax_label_menu"><strong><?php esc_html_e( 'Menu name', 'ccg-taxonomy' ); ?></strong></label>
            <input type="text"
                   id="ccgtax_label_menu"
                   name="ccgtax_label_menu"
                   class="widefat"
                   value="<?php echo esc_attr( $menu_name ); ?>"
                   placeholder="<?php esc_attr_e( 'Ex: Regiuni', 'ccg-taxonomy' ); ?>">
            <p class="description">
                <?php esc_html_e( 'Textul folosit în meniul de admin pentru această taxonomie.', 'ccg-taxonomy' ); ?>
            </p>
        </div>

        <div class="ccg-field-group">
            <label for="ccgtax_description"><strong><?php esc_html_e( 'Descriere (opțional)', 'ccg-taxonomy' ); ?></strong></label>
            <textarea id="ccgtax_description"
                      name="ccgtax_description"
                      class="widefat"
                      rows="3"><?php echo esc_textarea( $description ); ?></textarea>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Opțiuni taxonomie', 'ccg-taxonomy' ); ?></strong></label>

            <label>
                <input type="checkbox"
                       name="ccgtax_public"
                       value="1" <?php checked( $public, '1' ); ?>>
                <?php esc_html_e( 'Public (vizibil pe front-end)', 'ccg-taxonomy' ); ?>
            </label><br>

            <label>
                <input type="checkbox"
                       name="ccgtax_hierarchical"
                       value="1" <?php checked( $hierarchical, '1' ); ?>>
                <?php esc_html_e( 'Hierarchical (ex: categorii). Debifat = tags-like.', 'ccg-taxonomy' ); ?>
            </label><br>

            <label>
                <input type="checkbox"
                       name="ccgtax_show_admin_column"
                       value="1" <?php checked( $show_admin_col, '1' ); ?>>
                <?php esc_html_e( 'Afișează coloană în listarea de postări', 'ccg-taxonomy' ); ?>
            </label><br>

            <label>
                <input type="checkbox"
                       name="ccgtax_show_in_rest"
                       value="1" <?php checked( $show_in_rest, '1' ); ?>>
                <?php esc_html_e( 'Disponibilă în REST API / Gutenberg', 'ccg-taxonomy' ); ?>
            </label>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Post type-uri la care se aplică', 'ccg-taxonomy' ); ?></strong></label>
            <p class="description">
                <?php esc_html_e( 'Selectează post type-urile care vor folosi această taxonomie (ex: place, event, winery).', 'ccg-taxonomy' ); ?>
            </p>

            <div class="ccg-pt-list">
                <?php foreach ( $available_pts as $pt_name => $pt_label ) : ?>
                    <label>
                        <input type="checkbox"
                               name="ccgtax_post_types[]"
                               value="<?php echo esc_attr( $pt_name ); ?>"
                            <?php checked( in_array( $pt_name, $post_types_arr, true ) ); ?>>
                        <?php echo esc_html( $pt_label ); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ccg-field-group">
            <label><strong><?php esc_html_e( 'Importare termeni (SEED avansat)', 'ccg-taxonomy' ); ?></strong></label>

            <p class="description">
                <?php esc_html_e( 'Introdu un array PHP (simplu sau multidimensional). Acesta va popula taxonomia selectată.', 'ccg-taxonomy' ); ?>
            </p>

            <textarea name="ccgtax_seed_array" rows="10" style="width:100%;font-family:monospace;"><?php
                echo esc_textarea( get_post_meta( $post->ID, '_ccgtax_seed_array', true ) );
                ?></textarea>

            <p class="description">
                <?php esc_html_e( 'Exemplu valid:', 'ccg-taxonomy' ); ?>
            </p>

            <pre style="background:#f5f5f5;padding:10px;border-radius:4px;">
                    [
                      "Nord" => [
                          "Bălți",
                          "Edineț",
                          "Soroca"
                      ],
                      "Centru" => ["Chișinău", "Orhei"],
                      "Sud"
                    ]
            </pre>

            <p>
                <label>
                    <input type="checkbox" name="ccgtax_seed_replace" value="1">
                    <?php esc_html_e( 'Șterge termenii existenți înainte de import', 'ccg-taxonomy' ); ?>
                </label>
            </p>

            <button type="submit" class="button button-primary" name="ccgtax_run_seed" value="1">
                <?php esc_html_e( 'Execută SEED pentru taxonomie', 'ccg-taxonomy' ); ?>
            </button>
        </div>


        <p class="description">
            <?php esc_html_e( 'După salvare, această taxonomie va fi înregistrată și vei putea adăuga termeni prin submeniul din "Taxonomy Manager".', 'ccg-taxonomy' ); ?>
        </p>

    </div>

    <?php
}

/**
 * Salvare meta.
 */
function ccg_taxmgr_save_taxonomy_meta( $post_id ) {

    if ( ! isset( $_POST['ccg_taxmgr_meta_nonce'] ) ||
        ! wp_verify_nonce( $_POST['ccg_taxmgr_meta_nonce'], 'ccg_taxmgr_meta_save' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Slug
    if ( isset( $_POST['ccgtax_slug'] ) ) {
        $slug = sanitize_title( $_POST['ccgtax_slug'] );
        update_post_meta( $post_id, '_ccgtax_slug', $slug );
    }

    // Labels
    $fields = [
        'ccgtax_label_plural'   => '_ccgtax_label_plural',
        'ccgtax_label_singular' => '_ccgtax_label_singular',
        'ccgtax_label_menu'     => '_ccgtax_label_menu',
        'ccgtax_description'    => '_ccgtax_description',
    ];

    foreach ( $fields as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta(
                $post_id,
                $meta_key,
                sanitize_text_field( $_POST[ $field ] )
            );
        }
    }

    // Booleans
    $public          = isset( $_POST['ccgtax_public'] ) ? '1' : '0';
    $hierarchical    = isset( $_POST['ccgtax_hierarchical'] ) ? '1' : '0';
    $show_admin_col  = isset( $_POST['ccgtax_show_admin_column'] ) ? '1' : '0';
    $show_in_rest    = isset( $_POST['ccgtax_show_in_rest'] ) ? '1' : '0';

    update_post_meta( $post_id, '_ccgtax_public', $public );
    update_post_meta( $post_id, '_ccgtax_hierarchical', $hierarchical );
    update_post_meta( $post_id, '_ccgtax_show_admin_column', $show_admin_col );
    update_post_meta( $post_id, '_ccgtax_show_in_rest', $show_in_rest );

    // Post types
    if ( isset( $_POST['ccgtax_post_types'] ) && is_array( $_POST['ccgtax_post_types'] ) ) {
        $pts = array_map( 'sanitize_text_field', $_POST['ccgtax_post_types'] );
        $csv = implode( ',', $pts );
        update_post_meta( $post_id, '_ccgtax_post_types', $csv );
    } else {
        delete_post_meta( $post_id, '_ccgtax_post_types' );
    }

    // Save SEED array
    if ( isset( $_POST['ccgtax_seed_array'] ) ) {
        update_post_meta( $post_id, '_ccgtax_seed_array', $_POST['ccgtax_seed_array']  );
    }

    // If seed is triggered:
    if ( isset( $_POST['ccgtax_run_seed'] ) && '1' === $_POST['ccgtax_run_seed'] ) {

        $seed_code = get_post_meta( $post_id, '_ccgtax_seed_array', true );
        $taxonomy_slug = get_post_meta( $post_id, '_ccgtax_slug', true );
        if ( ! $taxonomy_slug ) {
            $taxonomy_slug = sanitize_title( get_post_field( 'post_title', $post_id ) );
        }

        // Parse array
        $seed_array = [];
        if ( ! empty( $seed_code ) ) {
            try {
                $seed_array = eval( "return $seed_code;" );
            } catch ( Exception $e ) {
                // Avoid fatal errors
                error_log( "Seed parsing error for taxonomy $taxonomy_slug" );
                return;
            }
        }

        if ( is_array( $seed_array ) ) {

            if ( isset( $_POST['ccgtax_seed_replace'] ) ) {
                // DELETE all terms before re-seeding
                $terms = get_terms([
                        'taxonomy' => $taxonomy_slug,
                        'hide_empty' => false
                ]);
                foreach ( $terms as $t ) {
                    wp_delete_term( $t->term_id, $taxonomy_slug );
                }
            }

            ccg_taxmgr_execute_seed_array( $taxonomy_slug, $seed_array );
        }
    }
}
