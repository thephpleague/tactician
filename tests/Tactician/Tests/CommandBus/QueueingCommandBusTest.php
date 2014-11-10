<?php

namespace Tactician\Tests\CommandBus;

use Mockery;
use Tactician\CommandBus\CommandBus;
use Tactician\CommandBus\QueueingCommandBus;
use Tactician\Tests\Fixtures\Command\TaskAddedCommand;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;

class QueueingCommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandBus|Mockery\MockInterface
     */
    private $innerCommandBus;

    /**
     * @var QueueingCommandBus|Mockery\MockInterface
     */
    private $queueingCommandBus;

    public function setup()
    {
        $this->innerCommandBus = Mockery::mock(CommandBus::class);

        $this->queueingCommandBus = new QueueingCommandBus(
            $this->innerCommandBus
        );
    }

    public function testInnerCommandBusReceivesCommand()
    {
        $command = new TaskAddedCommand();

        $this->innerCommandBus
            ->shouldReceive('execute')
            ->with($command)
            ->andReturn('foobar')
            ->once();

        $this->assertEquals(
            'foobar',
            $this->queueingCommandBus->execute($command)
        );
    }

    public function testCommandsAreQueuedIfAnotherCommandIsBeingExecuted()
    {
        $firstCommand = new TaskAddedCommand();
        $secondCommand = new TaskCompletedCommand();
        $secondCommandDispatched = false;

        $this->innerCommandBus
            ->shouldReceive('execute')
            ->with($firstCommand)
            ->andReturnUsing(
                function() use ($secondCommand, &$secondCommandDispatched) {
                    $this->queueingCommandBus->execute($secondCommand);
                    $secondCommandDispatched = true;
                    return 'first-payload';
                }
            )
            ->once();

        $this->innerCommandBus
            ->shouldReceive('execute')
            ->with($secondCommand)
            ->andReturnUsing(
                function () use (&$secondCommandDispatched) {
                    if (!$secondCommandDispatched) {
                        throw new \Exception('Second command was executed before the first completed!');
                    }
                    return 'second-payload';
                }
            )
            ->once();

        // Only the return value of the first command should be returned
        $this->assertEquals(
            'first-payload',
            $this->queueingCommandBus->execute($firstCommand)
        );
    }
}
