<?php

namespace CCG\Core;

use CCG\Core\Assets\Assets;
use CCG\Core\Roles\RolesManager;
use CCG\Core\PostTypes\PostTypeRegistrar;
use CCG\Core\Taxonomies\TaxonomyRegistrar;
use CCG\Core\Meta\MetaBoxManager;
use CCG\Core\REST\Router;
use CCG\Core\Blocks\BlockRegistrar;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin {

    /**
     * @var Plugin
     */
    protected static $instance;

    /** @var Assets */
    protected $assets;

    /** @var RolesManager */
    protected $roles;

    /** @var Router */
    protected $router;

    /** @var BlockRegistrar */
    protected $blocks;

    /**
     * Singleton
     */
    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Init plugin: înregistrăm hook-urile centrale
     */
    public function init()
    {

        // Manageri globali
        $this->assets = new Assets();
        $this->roles = new RolesManager();
        $this->router = new Router();
        $this->blocks = new BlockRegistrar();

        // Hook-uri globale
        add_action('init', [PostTypeRegistrar::class, 'register_all'], 5);
        add_action('init', [TaxonomyRegistrar::class, 'register_all'], 6);
        // Metabox-uri – doar în admin
        if (is_admin()) {
            MetaBoxManager::init();
        }


        // Assets admin/public
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueue_public']);

        // Roluri & capabilități
        add_action('init', [$this->roles, 'register_roles'], 2);
        register_activation_hook(CCG_CORE_FILE, [$this->roles, 'on_activation']);
        register_deactivation_hook(CCG_CORE_FILE, [$this->roles, 'on_deactivation']);

        // REST routes de bază (ex: healthcheck)
        add_action('rest_api_init', [$this->router, 'register_core_routes']);

        // Blocks comune (în viitor, ex: card-uri generice)
        add_action('init', [$this->blocks, 'register_blocks']);
    }
}
