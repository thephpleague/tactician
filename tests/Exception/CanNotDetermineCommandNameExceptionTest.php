<?php

namespace Tactician\CommandBus\Tests\Exception;

use League\Tactician\Exception\CanNotDetermineCommandNameException;

class CanNotDetermineCommandNameExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideAnyTypeOfCommand
     */
    public function testForAnyTypeOfCommand($command)
    {
        $exception = CanNotDetermineCommandNameException::forCommand($command);
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
