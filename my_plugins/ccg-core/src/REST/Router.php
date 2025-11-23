<?php
namespace CCG\Core\REST;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Router {

    const NAMESPACE = 'ccg/v1';

    public function register_core_routes() {
        register_rest_route(
            self::NAMESPACE,
            '/health',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'healthcheck' ],
                'permission_callback' => '__return_true',
            ]
        );
    }

    public function healthcheck( $request ) {
        return [
            'status'  => 'ok',
            'version' => CCG_CORE_VERSION,
        ];
    }
}
