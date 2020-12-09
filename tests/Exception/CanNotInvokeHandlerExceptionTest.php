<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Exception\Exception;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

class CanNotInvokeHandlerExceptionTest extends TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = CanNotInvokeHandlerException::forCommand($command, 'Because stuff');

        $this->assertStringContainsString(CompleteTaskCommand::class, $exception->getMessage());
        $this->assertStringContainsString('Because stuff', $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
        $this->assertInstanceOf(Exception::class, $exception);
    }

    /**
     * @dataProvider provideAnyTypeOfCommand
     */
    public function testForAnyTypeOfCommand($command)
    {
        $exception = CanNotInvokeHandlerException::forCommand($command, 'happens');
        $this->assertSame($command, $exception->getCommand());
    }

    public function provideAnyTypeOfCommand()
    {
        return [
            [ 1 ],
            [ new \stdClass() ],
            [ null ],
            [ 'a string' ],
            [ new \SplFileInfo(__FILE__) ],
            [ true ],
            [ false ],
            [ [] ],
            [ [ [ 1 ] ] ],
            [
                function () {
                }
            ],
        ];
    }
}
