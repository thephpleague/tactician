<?php

namespace League\Tactician\Tests\Plugins;

use Mockery;
use League\Tactician\Plugins\LockingMiddleware;
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

        $nextClosure = function ($command) {
            $this->assertTrue(is_object($command));
            return 'foobar';
        };

        $this->assertEquals(
            'foobar',
            $this->lockingMiddleware->execute($command, $nextClosure)
        );
    }

    public function testCommandsAreQueuedIfAnotherCommandIsBeingExecuted()
    {
        $secondCommandDispatched = false;

        $next2 = function ($command) use (&$secondCommandDispatched) {
            if (!$secondCommandDispatched) {
                throw new \Exception('Second command was executed before the first completed!');
            }
            return 'second-payload';
        };

        $next1 = function ($command) use (&$secondCommandDispatched, $next2) {
            // Technically, we'd pass the command back into the command bus, not the middleware
            // again but this it would result in the same thing with an extra test dependency.
            $this->lockingMiddleware->execute(new CompleteTaskCommand(), $next2);
            $secondCommandDispatched = true;
            return 'first-payload';
        };

        // Only the return value of the first command should be returned
        $this->assertEquals(
            'first-payload',
            $this->lockingMiddleware->execute(new AddTaskCommand(), $next1)
        );
    }
}
