<?php

namespace League\Tactician\CommandBus\Tests;

use Mockery;
use League\Tactician\CommandBus\CommandBus;
use League\Tactician\CommandBus\LockingCommandBus;
use League\Tactician\CommandBus\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;

class LockingCommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandBus|Mockery\MockInterface
     */
    private $innerCommandBus;

    /**
     * @var LockingCommandBus|Mockery\MockInterface
     */
    private $queueingCommandBus;

    public function setup()
    {
        $this->innerCommandBus = Mockery::mock(CommandBus::class);

        $this->queueingCommandBus = new LockingCommandBus(
            $this->innerCommandBus
        );
    }

    public function testInnerCommandBusReceivesCommand()
    {
        $command = new AddTaskCommand();

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
        $firstCommand = new AddTaskCommand();
        $secondCommand = new CompleteTaskCommand();
        $secondCommandDispatched = false;

        $firstExecution = function () use ($secondCommand, &$secondCommandDispatched) {
            $this->queueingCommandBus->execute($secondCommand);
            $secondCommandDispatched = true;
            return 'first-payload';
        };
        $this->innerCommandBus
            ->shouldReceive('execute')
            ->with($firstCommand)
            ->andReturnUsing($firstExecution)
            ->once();

        $secondExecution = function () use (&$secondCommandDispatched) {
            if (!$secondCommandDispatched) {
                throw new \Exception('Second command was executed before the first completed!');
            }
            return 'second-payload';
        };
        $this->innerCommandBus
            ->shouldReceive('execute')
            ->with($secondCommand)
            ->andReturnUsing($secondExecution)
            ->once();

        // Only the return value of the first command should be returned
        $this->assertEquals(
            'first-payload',
            $this->queueingCommandBus->execute($firstCommand)
        );
    }
}
