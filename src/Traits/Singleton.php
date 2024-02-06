<?php
declare(strict_types=1);
namespace Coine\WpHelper\Traits;

trait Singleton
{
    private static ?self $instance = null;

    public static function getInstance(): static
    {
        $class = __CLASS__;
        if (self::$instance === null) {
            self::$instance = new $class;
        }

        return self::$instance;
    }
}