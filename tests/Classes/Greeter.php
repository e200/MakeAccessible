<?php

namespace Tests;

/**
 * Class Quazar.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Greeter
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    protected function hasName()
    {
        return is_null($this->name);
    }

    public function greet()
    {
        return $this->hasName() ? 'Hello!' : 'Hello ' . $this->name . '!';
    }
}