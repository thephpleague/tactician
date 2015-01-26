<?php

namespace League\Tactician\Tests;

use Mockery;
use League\Tactician\CommandBus;
use League\Tactician\LockingMiddleware;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;

class LockingMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandBus|Mockery\MockInterface
     */
    private $nextClosure;

    /**
     * @var LockingMiddleware|Mockery\MockInterface
     */
    private $lockingMiddleware;

    public function setup()
    {
        $this->nextClosure = Mockery::mock(MockClosure::class);

        $this->lockingMiddleware = new LockingMiddleware(
            $this->nextClosure
        );
    }

    public function testInnerCommandBusReceivesCommand()
    {
        $command = new AddTaskCommand();

        $this->nextClosure
            ->shouldReceive('__invoke')
            ->andReturn('foobar')
            ->once();

        $this->assertEquals(
            'foobar',
            $this->lockingMiddleware->execute($command, $this->nextClosure)
        );
    }

    public function testCommandsAreQueuedIfAnotherCommandIsBeingExecuted()
    {
        $firstCommand = new AddTaskCommand();
        $secondCommand = new CompleteTaskCommand();
        $secondCommandDispatched = false;

        $next2 = function () use (&$secondCommandDispatched) {
            if (!$secondCommandDispatched) {
                throw new \Exception('Second command was executed before the first completed!');
            }
            return 'second-payload';
        };

        $next1 = function () use ($secondCommand, &$secondCommandDispatched, $next2) {
            $this->lockingMiddleware->execute($secondCommand, $next2);
            $secondCommandDispatched = true;
            return 'first-payload';
        };

        // Only the return value of the first command should be returned
        $this->assertEquals(
            'first-payload',
            $this->lockingMiddleware->execute($firstCommand, $next1)
        );
    }
}
