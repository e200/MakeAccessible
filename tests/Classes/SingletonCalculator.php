<?php

namespace Tests;

/**
 * Class SingletonCalculator.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class SingletonCalculator
{
    private $n;
    private $m;

    private static $instance;

    private function __construct($n, $m)
    {
        $this->n = $n;
        $this->m = $m;
    }

    private function __clone()
    {
    }

    public static function getInstance($n, $m)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($n, $m);
        }

        return self::$instance;
    }

    public function sum()
    {
        return $this->n + $this->m;
    }
}
