<?php

namespace League\Tactician\Tests\Handler;

use League\Tactician\Handler\HandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use stdClass;
use Mockery;

class HandlerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HandlerMiddleware
     */
    private $middleware;

    /**
     * @var HandlerLocator|Mockery\MockInterface
     */
    private $handlerLocator;

    /**
     * @var MethodNameInflector|Mockery\MockInterface
     */
    private $methodNameInflector;

    protected function setUp()
    {
        $this->handlerLocator = Mockery::mock(HandlerLocator::class);
        $this->methodNameInflector = Mockery::mock(MethodNameInflector::class);

        $this->middleware = new HandlerMiddleware(
            $this->handlerLocator,
            $this->methodNameInflector
        );
    }

    public function testHandlerIsExecuted()
    {
        $command = new CompleteTaskCommand();

        $handler = Mockery::mock(ConcreteMethodsHandler::class);
        $handler
            ->shouldReceive('handleCompleteTaskCommand')
            ->with($command)
            ->once()
            ->andReturn('a-return-value');

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->withArgs([$command, $handler])
            ->andReturn('handleCompleteTaskCommand');

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->with($command)
            ->andReturn($handler);

        $this->assertEquals('a-return-value', $this->middleware->execute($command, $this->mockNext()));
    }

    /**
     * @expectedException \League\Tactician\Exception\CanNotInvokeHandlerException
     */
    public function testMissingMethodOnHandlerObjectIsDetected()
    {
        $command = new CompleteTaskCommand();

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->andReturn('someMethodThatDoesNotExist');

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->andReturn(new stdClass);

        $this->assertEquals('a-return-value', $this->middleware->execute($command, $this->mockNext()));
    }

    public function testDynamicMethodNamesAreSupported()
    {
        $command = new CompleteTaskCommand();
        $handler = new DynamicMethodsHandler();

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->andReturn('someHandlerMethod');

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->andReturn($handler);

        $this->middleware->execute($command, $this->mockNext());

        $this->assertEquals(
            ['someHandlerMethod'],
            $handler->getMethodsInvoked()
        );
    }

    public function testClosuresCanBeInvoked()
    {
        $command = new CompleteTaskCommand();
        $closureWasExecuted = false;
        $handler = function () use (&$closureWasExecuted) {
            $closureWasExecuted = true;
        };

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->andReturn('__invoke');

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->andReturn($handler);

        $this->middleware->execute($command, $this->mockNext());

        $this->assertTrue($closureWasExecuted);
    }

    /**
     * @return callable
     */
    protected function mockNext()
    {
        return function() {
            throw new \LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
