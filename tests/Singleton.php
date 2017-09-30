<?php

namespace Tests;

/**
 * Class Singleton.
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

    public function getMessage()
    {
        return "Hi! I'm a singleton instance";
    }
}
