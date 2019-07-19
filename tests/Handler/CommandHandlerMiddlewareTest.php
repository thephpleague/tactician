<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler;

use League\Tactician\Handler\CanNotInvokeHandler;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\ClassName\ClassNameInflector;
use League\Tactician\Handler\MethodName\MethodNameInflector;
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

    /** @var ClassNameInflector&MockObject */
    private $classNameInflector;

    /** @var ContainerInterface&MockObject */
    private $container;

    /** @var MethodNameInflector&MockObject */
    private $methodNameInflector;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->classNameInflector = $this->createMock(ClassNameInflector::class);
        $this->methodNameInflector = $this->createMock(MethodNameInflector::class);

        $this->middleware = new CommandHandlerMiddleware(
            $this->container,
            $this->classNameInflector,
            $this->methodNameInflector
        );
    }

    public function testHandlerIsExecuted(): void
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
            ->with(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
            ->willReturn('handleTaskCompletedCommand');

        $this->container
            ->method('get')
            ->with(ConcreteMethodsHandler::class)
            ->willReturn($handler);

        $this->classNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class)
            ->willReturn(ConcreteMethodsHandler::class);

        self::assertEquals('a-return-value', $this->middleware->execute($command, $this->mockNext()));
    }

    public function testMissingMethodOnHandlerObjectIsDetected(): void
    {
        $command = new CompleteTaskCommand();

        $this->methodNameInflector
            ->method('inflect')
            ->willReturn('someMethodThatDoesNotExist');

        $this->container
            ->method('get')
            ->willReturn(new stdClass());

        $this->classNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class);

        $this->expectException(CanNotInvokeHandler::class);
        $this->middleware->execute($command, $this->mockNext());
    }

    public function testDynamicMethodNamesAreSupported(): void
    {
        $command = new CompleteTaskCommand();
        $handler = new DynamicMethodsHandler();

        $this->methodNameInflector
            ->method('inflect')
            ->willReturn('someHandlerMethod');

        $this->container
            ->method('get')
            ->willReturn($handler);

        $this->classNameInflector
            ->method('getHandlerClassName')
            ->with(CompleteTaskCommand::class);

        $this->middleware->execute($command, $this->mockNext());

        self::assertEquals(
            ['someHandlerMethod'],
            $handler->getMethodsInvoked()
        );
    }

    protected function mockNext(): callable
    {
        return static function (): void {
            throw new LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
