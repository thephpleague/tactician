<?php
namespace Tactician\CommandBus\Tests\Exception;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Exception\Exception;

class MissingHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = MissingHandlerException::forCommand($command);

        $this->assertContains(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
