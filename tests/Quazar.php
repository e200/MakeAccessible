<?php

namespace Tests;

/**
 * Class Quazar.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Quazar
{
    private   $lightSpeed     = '299.792.458 m/s';
    protected $alphaCentaurus = '4.37 l/y';

    private function sayHi($name)
    {
        return "Hi {$name}!";
    }

    protected function helloUniverse()
    {
        return 'Hello Universe!';
    }
}
