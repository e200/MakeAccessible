<?php

namespace Tests;

class Inaccessible
{
    private $privateProperty = true;
    protected $protectedProperty = true;

    private $privateArray = [true, false];
    
    private function privateMethod()
    {
        return true;
    }

    protected function protectedMethod()
    {
        return true;
    }
}