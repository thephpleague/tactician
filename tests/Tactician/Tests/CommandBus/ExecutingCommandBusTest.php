<?php

namespace Tactician\Tests\CommandBus;

use Tactician\CommandBus\ExecutingCommandBus;
use Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Tactician\Handler\Locator\HandlerLocator;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;
use Tactician\Tests\Fixtures\Handler\TaskCompletedHandler;
use Mockery;

class ExecutingCommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutingCommandBus
     */
    private $commandBus;

    /**
     * @var HandlerLocator|Mockery\MockInterface
     */
    private $handlerLocator;

    /**
     * @var InvokingStrategy|Mockery\MockInterface
     */
    private $methodNameInflector;

    protected function setUp()
    {
        $this->handlerLocator = Mockery::mock(HandlerLocator::class);
        $this->methodNameInflector = Mockery::mock(MethodNameInflector::class);

        $this->commandBus = new ExecutingCommandBus(
            $this->handlerLocator,
            $this->methodNameInflector
        );
    }

    public function testHandlerIsExecuted()
    {
        $command = new TaskCompletedCommand();

        $handler = Mockery::mock(TaskCompletedHandler::class);
        $handler
            ->shouldReceive('handleTaskCompletedCommand')
            ->with($command)
            ->once()
            ->andReturn('a-return-value');

        $this->methodNameInflector
            ->shouldReceive('inflect')
            ->withArgs([$command, $handler])
            ->andReturn('handleTaskCompletedCommand');

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->with($command)
            ->andReturn($handler);

        $this->assertEquals('a-return-value', $this->commandBus->execute($command));
    }
}
