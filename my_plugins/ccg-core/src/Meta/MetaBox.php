<?php
namespace CCG\Core\Meta;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MetaBox {

    public $id;
    public $title;
    public $screen;
    public $context;
    public $priority;
    public $callback;      // callable pentru afișare
    public $save_callback; // callable pentru salvare

    public function __construct( $id, $title, $screen, $callback, $save_callback, $context = 'advanced', $priority = 'default' ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->screen        = (array) $screen;
        $this->callback      = $callback;
        $this->save_callback = $save_callback;
        $this->context       = $context;
        $this->priority      = $priority;
    }
}
