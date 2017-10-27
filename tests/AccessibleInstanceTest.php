<?php

use Tests\Inaccessible;
use PHPUnit\Framework\TestCase;
use e200\MakeAccessible\Reflector;
use e200\MakeAccessible\AccessibleInstance;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;

class AccessibleInstanceTest extends TestCase
{
    public function testInvokeProtectedMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertTrue($accessibleInstance->protectedMethod());
    }

    public function testInvokePrivateMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertTrue($accessibleInstance->privateMethod());
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

        $this->assertTrue($accessibleInstance->protectedProperty);
    }

    public function testGetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertTrue($accessibleInstance->privateProperty);
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

        $this->assertTrue($accessibleInstance->protectedProperty);

        $accessibleInstance->protectedProperty = false;

        $this->assertFalse($accessibleInstance->protectedProperty);
    }

    public function testSetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        
        $this->assertTrue($accessibleInstance->privateProperty);

        $accessibleInstance->privateProperty = false;

        $this->assertFalse($accessibleInstance->privateProperty);
    }

    public function testGetPrivateArrayProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        
        $this->assertTrue($accessibleInstance->privateArray[0]);
        $this->assertFalse($accessibleInstance->privateArray[1]);
    }

    /**
     * @covers AccessibleInstance::setArrayPropertyValue()
     */
    public function testSetPrivateArrayProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        
        $this->assertFalse($accessibleInstance->privateArray[1]);

        $accessibleInstance->setArrayPropertyValue('privateArray', 1, true);

        $this->assertTrue($accessibleInstance->privateArray[1]);
    }

    public function testPropertyNotFoundNotFoundExceptionOnSetProperty()
    {
        $this->expectException(PropertyNotFoundNotFoundException::class);
        
        $accessibleInstance = $this->getAccessibleInstance();

        $accessibleInstance->unknowProperty;
    }

    public function getInstance()
    {
        return new Inaccessible();
    }

    public function getAccessibleInstance($instance = null)
    {
        if (!$instance) {
            $instance = $this->getInstance();
        }

        return new AccessibleInstance($instance, new Reflector($instance));
    }
}
