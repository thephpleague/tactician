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
     * @var LockingMiddleware
     */
    private $lockingMiddleware;

    public function setup()
    {
        $this->lockingMiddleware = new LockingMiddleware();
    }

    public function testInnerCommandBusReceivesCommand()
    {
        $command = new AddTaskCommand();

        $nextClosure = function () {
            return 'foobar';
        };

        $this->assertEquals(
            'foobar',
            $this->lockingMiddleware->execute($command, $nextClosure)
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
