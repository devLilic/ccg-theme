<?php
namespace CCG\Core\Roles;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RolesManager {

    public function register_roles() {
        // Deocamdată nu adăugăm roluri noi,
        // dar aici putem defini în viitor roluri speciale (ex: editor turistic).
    }

    public function on_activation() {
        $this->register_roles();
        flush_rewrite_rules();
    }

    public function on_deactivation() {
        flush_rewrite_rules();
    }
}
