<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\CanNotInvokeHandler;
use League\Tactician\Exception\Exception;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;
use stdClass;

class CanNotInvokeHandlerExceptionTest extends TestCase
{
    public function testExceptionContainsDebuggingInfo() : void
    {
        $command = new CompleteTaskCommand();

        $exception = CanNotInvokeHandler::forCommand($command, 'Because stuff');

        self::assertStringContainsString(CompleteTaskCommand::class, $exception->getMessage());
        self::assertStringContainsString('Because stuff', $exception->getMessage());
        self::assertSame($command, $exception->getCommand());
        self::assertInstanceOf(Exception::class, $exception);
    }

    public function testForAnyTypeOfCommand() : void
    {
        $exception = CanNotInvokeHandler::forCommand($command = new stdClass(), 'happens');
        self::assertSame($command, $exception->getCommand());
    }
}
