<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use PHPUnit\Framework\TestCase;

class CallableLocatorTest extends TestCase
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @var CallableLocator
     */
    private $callableLocator;

    protected function setUp()
    {
        $this->handlers = [
            'add.task' => new DynamicMethodsHandler(),
            'complete.task' => new ConcreteMethodsHandler(),
            'missing.command' => null
        ];

        $callable = function ($commandName) {
            return $this->handlers[$commandName];
        };

        $this->callableLocator = new CallableLocator($callable);
    }


    public function testLocatorCanRetrieveHandler()
    {
        $this->assertSame(
            $this->handlers['complete.task'],
            $this->callableLocator->getHandlerForCommand('complete.task')
        );
    }

    /**
     * @expectedException \League\Tactician\Exception\MissingHandlerException
     */
    public function testMissingHandlerCausesException()
    {
        $this->callableLocator->getHandlerForCommand('missing.command');
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testExceptionsFromCallableBubbleUp()
    {
        $callable = function () {
            throw new \RuntimeException();
        };

        (new CallableLocator($callable))->getHandlerForCommand('foo');
    }

    public function testAcceptsArrayCallables()
    {
        $handler = new ConcreteMethodsHandler();
        $container = new \ArrayObject(['foo' => $handler]);

        $this->assertSame(
            $handler,
            (new CallableLocator([$container, 'offsetGet']))->getHandlerForCommand('foo')
        );
    }
}
