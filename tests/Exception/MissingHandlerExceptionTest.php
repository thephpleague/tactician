<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\MissingHandlerException;

class MissingHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURE_COMMAND_CLASS = 'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand';

    public function testExceptionContainsDebuggingInfo()
    {
        $exception = MissingHandlerException::forCommand(self::FIXTURE_COMMAND_CLASS);

        $this->assertContains(self::FIXTURE_COMMAND_CLASS, $exception->getMessage());
        $this->assertSame(self::FIXTURE_COMMAND_CLASS, $exception->getCommandName());
        $this->assertInstanceOf('League\\Tactician\\Exception\\Exception', $exception);
    }
}
