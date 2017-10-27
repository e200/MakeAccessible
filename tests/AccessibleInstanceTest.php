<?php

use e200\MakeAccessible\AccessibleInstance;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use e200\MakeAccessible\Reflector;
use PHPUnit\Framework\TestCase;
use Tests\Quazar;

class AccessibleInstanceTest extends TestCase
{
    public function testInvokeProtectedMethod()
    {
        $instance = $this->getEncapsulatedInstance();

        $accessibleInstance = $this->getAccessibleInstance($instance);

        $this->assertEquals('Hello Universe!', $accessibleInstance->helloUniverse());
    }

    public function testInvokePrivateMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('Hi Eleandro!', $accessibleInstance->sayHi('Eleandro'));
    }

    public function testMethodNotFoundNotFoundExceptionOnInvokeMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(MethodNotFoundException::class);

        $accessibleInstance->blackHole();
    }

    public function testGetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('4.37 l/y', $accessibleInstance->alphaCentaurus);
    }

    public function testGetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('299.792.458 m/s', $accessibleInstance->lightSpeed);
    }

    public function testPropertyNotFoundNotFoundExceptionOnAccessProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa;
    }

    public function testSetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedValue = 'Wow!!! Omega centaurus! :o';

        $accessibleInstance->alphaCentaurus = $expectedValue;

        $this->assertEquals($expectedValue, $accessibleInstance->alphaCentaurus);
    }

    public function testSetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedValue = 200;

        $accessibleInstance->lightSpeed = $expectedValue;

        $this->assertEquals($expectedValue, $accessibleInstance->lightSpeed);
    }

    public function testPropertyNotFoundNotFoundExceptionOnSetProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa = 'Eye';
    }

    public function getEncapsulatedInstance()
    {
        return new Quazar();
    }

    public function getAccessibleInstance($instance = null)
    {
        if (!$instance) {
            $instance = $this->getEncapsulatedInstance();
        }

        return new AccessibleInstance($instance, new Reflector($instance));
    }
}
