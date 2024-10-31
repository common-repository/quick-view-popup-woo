<?php
namespace WPVQV;

use WPVQV\Frontend\Module as Frontend;

class Plugin
{
    private static $instance;

    public static function init()
    {
        if (null === self::$instance) {
            self::$instance = new Plugin();
        }

        return self::$instance;
    }

    // The Constructor.
    public function __construct()
    {
        new Frontend();
    }
}

Plugin::init();

