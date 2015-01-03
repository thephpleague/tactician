<?php
namespace Tactician\CommandBus\Tests\Exception;

use Tactician\CommandBus\Exception\CanNotInvokeHandlerException;
use Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;

class CanNotInvokeHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = CanNotInvokeHandlerException::onObject($command, 'Because stuff');

        $this->assertContains(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertContains('Because stuff', $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
    }
}
