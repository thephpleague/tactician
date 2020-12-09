<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Exception\Exception;
use PHPUnit\Framework\TestCase;

class MissingHandlerExceptionTest extends TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $exception = MissingHandlerException::forCommand(CompleteTaskCommand::class);

        $this->assertStringContainsString(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertSame(CompleteTaskCommand::class, $exception->getCommandName());
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
