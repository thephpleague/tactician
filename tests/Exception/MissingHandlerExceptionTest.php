<?php

namespace Tactician\CommandBus\Tests\Exception;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Exception\Exception;

class MissingHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $exception = MissingHandlerException::forCommand(CompleteTaskCommand::class);

        $this->assertContains(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertSame(CompleteTaskCommand::class, $exception->getCommandName());
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
