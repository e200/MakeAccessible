<?php

use Tests\Greeter;
use Tests\Singleton;
use e200\MakeAccessible\Make;
use Tests\SingletonCalculator;
use PHPUnit\Framework\TestCase;
use e200\MakeAccessible\AccessibleInstance;
use e200\MakeAccessible\Exceptions\InvalidInstanceException;
use e200\MakeAccessible\Exceptions\InvalidSingletonClassNameException;

/**
 * Class MakeTest.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class MakeTest extends TestCase
{
    /**
     * @covers Make::accessible()
     */
    public function testAccessible()
    {
        $instance = new Greeter();

        $accessibleInstance = Make::accessible($instance);

        $this->assertInstanceOf(AccessibleInstance::class, $accessibleInstance);
    }

    /**
     * @covers Make::accessible()
     */
    public function testInvalidObjectInstanceExceptionOnInvalidInstanceProvided()
    {
        $this->expectException(InvalidInstanceException::class);

        Make::accessible(null);
    }

    /**
     * @covers Make::instance()
     */
    public function testMakeSingletonInstance()
    {
        $instance = Make::instance(Singleton::class);

        $this->assertInstanceOf(Singleton::class, $instance);
        $this->assertTrue($instance->isOk());
    }

    /**
     * @covers Make::instance()
     */
    public function testSingletonClassInstanceWithArgs()
    {
        $instance = Make::instance(SingletonCalculator::class, [1, 2]);

        $this->assertInstanceOf(SingletonCalculator::class, $instance);
        $this->assertEquals(3, $instance->sum());
    }

    /**
     * @covers Make::instance()
     */
    public function testWrong()
    {
        $this->expectException(InvalidSingletonClassNameException::class);

        Make::instance(null);
    }

    /**
     * @covers Make::accessibleInstance()
     */
    public function testAccessibleInstance()
    {
        $instance = Make::accessibleInstance(Singleton::class);

        $this->assertTrue($instance->isAccessible());
    }
}
