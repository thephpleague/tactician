<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler;

use League\Tactician\Exception\CanNotInvokeHandler;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
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

    /** @var CommandNameExtractor&MockObject */
    private $commandNameExtractor;

    /** @var ContainerInterface&MockObject */
    private $container;

    /** @var MethodNameInflector&MockObject */
    private $methodNameInflector;

    protected function setUp() : void
    {
        $this->commandNameExtractor = $this->createMock(CommandNameExtractor::class);
        $this->container            = $this->createMock(ContainerInterface::class);
        $this->methodNameInflector  = $this->createMock(MethodNameInflector::class);

        $this->middleware = new CommandHandlerMiddleware(
            $this->commandNameExtractor,
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
            ->with(CompleteTaskCommand::class)
            ->willReturn($handler);

        $this->commandNameExtractor
            ->method('extract')
            ->with($command)
            ->willReturn(CompleteTaskCommand::class);

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

        $this->commandNameExtractor
            ->method('extract')
            ->with($command);

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

        $this->commandNameExtractor
            ->method('extract')
            ->with($command);

        $this->middleware->execute($command, $this->mockNext());

        self::assertEquals(
            ['someHandlerMethod'],
            $handler->getMethodsInvoked()
        );
    }

    public function testClosuresCanBeInvoked() : void
    {
        $command            = new CompleteTaskCommand();
        $closureWasExecuted = false;
        $handler            = static function () use (&$closureWasExecuted) : void {
            $closureWasExecuted = true;
        };

        $this->methodNameInflector
            ->method('inflect')
            ->willReturn('__invoke');

        $this->container
            ->method('get')
            ->willReturn($handler);

        $this->commandNameExtractor
            ->method('extract')
            ->with($command);

        $this->middleware->execute($command, $this->mockNext());

        self::assertTrue($closureWasExecuted);
    }

    protected function mockNext() : callable
    {
        return static function () : void {
            throw new LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
