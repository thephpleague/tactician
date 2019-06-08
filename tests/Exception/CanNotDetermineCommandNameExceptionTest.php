<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\CanNotDetermineCommandNameException;
use Mockery;
use PHPUnit\Framework\TestCase;

class CanNotDetermineCommandNameExceptionTest extends TestCase
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

    public function tearDown(): void
    {
        Mockery::close();
    }
}
