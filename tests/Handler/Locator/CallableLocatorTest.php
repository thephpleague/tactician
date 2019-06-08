<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use Mockery;
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

    protected function setUp(): void
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

    public function testMissingHandlerCausesException()
    {
        $this->expectException(MissingHandlerException::class);

        $this->callableLocator->getHandlerForCommand('missing.command');
    }

    public function testExceptionsFromCallableBubbleUp()
    {
        $callable = function () {
            throw new \RuntimeException();
        };

        $this->expectException(\RuntimeException::class);
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

    public function tearDown(): void
    {
        Mockery::close();
    }
}
