<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\HandlerNameInflector;

use League\Tactician\Handler\ClassName\Suffix;
use PHPUnit\Framework\TestCase;

class SuffixTest extends TestCase
{
    /** @dataProvider examples */
    public function testCanAddSuffixToClassName(string $suffix, string $command, string $expectedResult) : void
    {
        self::assertEquals(
            $expectedResult,
            (new Suffix($suffix))->getHandlerClassName($command)
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
