<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\ClassName;

use League\Tactician\Handler\Mapping\ClassName\Suffix;
use PHPUnit\Framework\TestCase;

class SuffixTest extends TestCase
{
    /** @dataProvider examples */
    public function testCanAddSuffixToClassName(string $suffix, string $command, string $expectedResult) : void
    {
        self::assertEquals(
            $expectedResult,
            (new Suffix($suffix))->getClassName($command)
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
