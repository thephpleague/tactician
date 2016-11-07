<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;

class CanNotInvokeHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = new CompleteTaskCommand();

        $exception = CanNotInvokeHandlerException::forCommand($command, 'Because stuff');

        $this->assertContains(get_class($command), $exception->getMessage());
        $this->assertContains('Because stuff', $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
        $this->assertInstanceOf('League\\Tactician\\Exception\\Exception', $exception);
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
