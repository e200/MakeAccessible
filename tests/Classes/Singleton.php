<?php

namespace Tests;

/**
 * Class Singleton.
 *
 * A class that uses the singleton pattern.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Singleton
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Used to check if `Make::accessibleInstance()` works.
     */
    public function isOk()
    {
        return true;
    }

    /**
     * Used to check if `Make::accessibleInstance()` works.
     */
    private function isAccessible()
    {
        return true;
    }
}
