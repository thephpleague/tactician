<?php

namespace Tactician\Tests\CommandBus;

use Tactician\CommandBus\ExecutingCommandBus;
use Tactician\Handler\InvokingStrategy\InvokingStrategy;
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
    private $invokingStrategy;

    protected function setUp()
    {
        $this->handlerLocator = Mockery::mock(HandlerLocator::class);
        $this->invokingStrategy = Mockery::mock(InvokingStrategy::class);

        $this->commandBus = new ExecutingCommandBus(
            $this->handlerLocator,
            $this->invokingStrategy
        );
    }

    public function testHandlerIsExecuted()
    {
        $command = new TaskCompletedCommand();
        $handler = new TaskCompletedHandler();

        $this->handlerLocator
            ->shouldReceive('getHandlerForCommand')
            ->with($command)
            ->andReturn($handler);

        $this->invokingStrategy
            ->shouldReceive('execute')
            ->withArgs([$command, $handler])
            ->andReturn('foobar');

        $this->assertEquals('foobar', $this->commandBus->execute($command));
    }
}
