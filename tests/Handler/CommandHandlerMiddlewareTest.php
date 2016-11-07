<?php

namespace League\Tactician\Tests\Handler;

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use stdClass;
use Mockery;

class CommandHandlerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandHandlerMiddleware
     */
    private $middleware;

    /**
     * @var CommandNameExtractor|Mockery\MockInterface
     */
    private $commandNameExtractor;

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
        $this->commandNameExtractor = Mockery::mock(
            'League\\Tactician\\Handler\\CommandNameExtractor\\CommandNameExtractor'
        );
        $this->handlerLocator = Mockery::mock('League\\Tactician\\Handler\\Locator\\HandlerLocator');
        $this->methodNameInflector = Mockery::mock(
            'League\\Tactician\\Handler\\MethodNameInflector\\MethodNameInflector'
        );

        $this->middleware = new CommandHandlerMiddleware(
            $this->commandNameExtractor,
            $this->handlerLocator,
            $this->methodNameInflector
        );
    }

    public function testHandlerIsExecuted()
    {
        $command = new CompleteTaskCommand();

        $handler = Mockery::mock('League\\Tactician\\Tests\\Fixtures\\Handler\\ConcreteMethodsHandler');
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
            ->with('League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand')
            ->andReturn($handler);

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command)
            ->andReturn('League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand');

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

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command);

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

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command);

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

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command);

        $this->middleware->execute($command, $this->mockNext());

        $this->assertTrue($closureWasExecuted);
    }

    /**
     * @return callable
     */
    protected function mockNext()
    {
        return function () {
            throw new \LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
