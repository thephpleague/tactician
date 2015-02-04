<?php

namespace Tactician\CommandBus\Tests\Exception;

use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Exception\Exception;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;

class CanNotInvokeHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = CanNotInvokeHandlerException::forCommand($command, 'Because stuff');

        $this->assertContains(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertContains('Because stuff', $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
