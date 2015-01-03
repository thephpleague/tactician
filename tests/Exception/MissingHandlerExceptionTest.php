<?php
namespace Tactician\CommandBus\Tests\Exception;

use Tactician\CommandBus\Exception\MissingHandlerException;
use Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;

class MissingHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = MissingHandlerException::forCommand($command);

        $this->assertContains(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
    }
}
