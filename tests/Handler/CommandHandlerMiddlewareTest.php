<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler;

use League\Tactician\Exception\CanNotInvokeHandler;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\HandlerNameInflector\HandlerNameInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;

class CommandHandlerMiddlewareTest extends TestCase
{
    /** @var CommandHandlerMiddleware */
    private $middleware;

    /** @var HandlerNameInflector&MockObject */
    private $handlerNameInflector;

    /** @var ContainerInterface&MockObject */
    private $container;

    /** @var MethodNameInflector&MockObject */
    private $methodNameInflector;

    protected function setUp() : void
    {
        $this->handlerNameInflector = $this->createMock(HandlerNameInflector::class);
        $this->container            = $this->createMock(ContainerInterface::class);
        $this->methodNameInflector  = $this->createMock(MethodNameInflector::class);

        $this->middleware = new CommandHandlerMiddleware(
            $this->handlerNameInflector,
            $this->container,
            $this->methodNameInflector
        );
    }

    public function testHandlerIsExecuted() : void
    {
        $command = new CompleteTaskCommand();

        $handler = $this->createMock(ConcreteMethodsHandler::class);
        $handler
            ->expects(self::once())
            ->method('handleTaskCompletedCommand')
            ->with($command)
            ->willReturn('a-return-value');

        $this->methodNameInflector
            ->method('inflect')
            ->with($command, $handler)
            ->willReturn('handleTaskCompletedCommand');

        $this->container
            ->method('get')
            ->with(ConcreteMethodsHandler::class)
            ->willReturn($handler);

        $this->handlerNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class)
            ->willReturn(ConcreteMethodsHandler::class);

        self::assertEquals('a-return-value', $this->middleware->execute($command, $this->mockNext()));
    }

    public function testMissingMethodOnHandlerObjectIsDetected() : void
    {
        $command = new CompleteTaskCommand();

        $this->methodNameInflector
            ->method('inflect')
            ->willReturn('someMethodThatDoesNotExist');

        $this->container
            ->method('get')
            ->willReturn(new stdClass());

        $this->handlerNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class);

        $this->expectException(CanNotInvokeHandler::class);
        $this->middleware->execute($command, $this->mockNext());
    }

    public function testDynamicMethodNamesAreSupported() : void
    {
        $command = new CompleteTaskCommand();
        $handler = new DynamicMethodsHandler();

        $this->methodNameInflector
            ->method('inflect')
            ->willReturn('someHandlerMethod');

        $this->container
            ->method('get')
            ->willReturn($handler);

        $this->handlerNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class);

        $this->middleware->execute($command, $this->mockNext());

        self::assertEquals(
            ['someHandlerMethod'],
            $handler->getMethodsInvoked()
        );
    }

    protected function mockNext() : callable
    {
        return static function () : void {
            throw new LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
