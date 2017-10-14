<?php

namespace League\Tactician\Tests\Plugins;

use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

class LockingMiddlewareTest extends TestCase
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

    public function testSecondsCommandIsNotDispatchedUntilFirstCommandIsComplete()
    {
        $secondCommandDispatched = false;

        $next2 = function () use (&$secondCommandDispatched) {
            if (!$secondCommandDispatched) {
                throw new \Exception('Second command was executed before the first completed!');
            }
        };

        $next1 = function () use (&$secondCommandDispatched, $next2) {
            $this->lockingMiddleware->execute(null, $next2);
            $secondCommandDispatched = true;
        };

        $this->lockingMiddleware->execute(null, $next1);
    }

    public function testTheReturnValueOfTheFirstCommandIsGivenBack()
    {
        $next2 = function () {
            return 'second-payload';
        };

        $next1 = function () use ($next2) {
            $this->lockingMiddleware->execute(null, $next2);
            return 'first-payload';
        };

        // Only the return value of the first command should be returned
        $this->assertEquals(
            'first-payload',
            $this->lockingMiddleware->execute(null, $next1)
        );
    }

    public function testTheCorrectSubCommandIsGivenToTheNextCallable()
    {
        $secondCommand = new CompleteTaskCommand();

        $next2 = function ($command) use ($secondCommand) {
            if ($command !== $secondCommand) {
                throw new \Exception('Received incorrect command: ' . get_class($command));
            }
        };

        $next1 = function () use ($next2, $secondCommand) {
            $this->lockingMiddleware->execute($secondCommand, $next2);
        };

        $this->lockingMiddleware->execute(null, $next1);
    }

    public function testExceptionsDoNotLeaveTheCommandBusLocked()
    {
        $next = function () {
            throw new \Exception();
        };

        try {
            $this->lockingMiddleware->execute(new AddTaskCommand(), $next);
        } catch (\Exception $e) {
        }

        $next2 = function () use (&$executed) {
            return true;
        };
        $this->assertTrue(
            $this->lockingMiddleware->execute(new AddTaskCommand(), $next2),
            'Second command was not executed'
        );
    }

    public function testExceptionsDoNotLeaveQueuedCommandsInTheBus()
    {
        $next = function () {

            $this->lockingMiddleware->execute(
                new CompleteTaskCommand(),
                function () {
                    throw new \Exception('This $next gets queued but should never be triggered');
                }
            );

            throw new \Exception('Exit out, thus dropping the queue');
        };

        // Now we create an error and suppress the exception...
        try {
            $this->lockingMiddleware->execute(new AddTaskCommand(), $next);
        } catch (\Exception $e) {
        }

        // Then check the next command is executed but the queued one never is
        $next2 = function () use (&$executed) {
            return true;
        };
        $this->assertTrue(
            $this->lockingMiddleware->execute(new AddTaskCommand(), $next2),
            'Next pending command was not executed'
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionsAreAllowedToBubbleUp()
    {
        $next = function () {
            throw new \LogicException('Exit out, thus dropping the queue');
        };

        $this->lockingMiddleware->execute(new AddTaskCommand(), $next);
    }
}
