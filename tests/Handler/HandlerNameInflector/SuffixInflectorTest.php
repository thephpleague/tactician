<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\HandlerNameInflector;

use League\Tactician\Handler\HandlerNameInflector\SuffixInflector;
use PHPUnit\Framework\TestCase;

class SuffixInflectorTest extends TestCase
{
    /** @dataProvider examples */
    public function testCanAddSuffixToClassName(string $suffix, string $command, string $expectedResult) : void
    {
        self::assertEquals(
            $expectedResult,
            (new SuffixInflector($suffix))->getHandlerClassName($command)
        );
    }

    /** @return array<array<string>> */
    public function examples() : array
    {
        return [
            ['Handler', 'SomeCommand', 'SomeCommandHandler'],
            ['', 'SomeCommand', 'SomeCommand'],
        ];
    }
}
