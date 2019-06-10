<?php

namespace League\Tactician\Tests\Handler;

use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;
use Mockery;

class CommandHandlerMiddlewareTest extends TestCase
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
     * @var ContainerInterface|Mockery\MockInterface
     */
    private $container;

    /**
     * @var MethodNameInflector|Mockery\MockInterface
     */
    private $methodNameInflector;

    protected function setUp(): void
    {
        $this->commandNameExtractor = Mockery::mock(CommandNameExtractor::class);
        $this->container = Mockery::mock(ContainerInterface::class);
        $this->methodNameInflector = Mockery::mock(MethodNameInflector::class);

        $this->middleware = new CommandHandlerMiddleware(
            $this->commandNameExtractor,
            $this->container,
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

        $this->container
            ->shouldReceive('get')
            ->with(CompleteTaskCommand::class)
            ->andReturn($handler);

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command)
            ->andReturn(CompleteTaskCommand::class);

        $this->assertEquals('a-return-value', $this->middleware->execute($command, $this->mockNext()));
    }

    public function testMissingMethodOnHandlerObjectIsDetected()
    {
        $command = new CompleteTaskCommand();

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->andReturn('someMethodThatDoesNotExist');

        $this->container
            ->shouldReceive('get')
            ->andReturn(new stdClass);

        $this->commandNameExtractor
            ->shouldReceive('extract')
            ->with($command);

        $this->expectException(CanNotInvokeHandlerException::class);
        $this->middleware->execute($command, $this->mockNext());
    }

    public function testDynamicMethodNamesAreSupported()
    {
        $command = new CompleteTaskCommand();
        $handler = new DynamicMethodsHandler();

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->andReturn('someHandlerMethod');

        $this->container
            ->shouldReceive('get')
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

        $this->container
            ->shouldReceive('get')
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

    public function tearDown(): void
    {
        Mockery::close();
    }
}
