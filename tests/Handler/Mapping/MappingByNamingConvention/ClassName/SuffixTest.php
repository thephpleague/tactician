<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\ClassName;

use League\Tactician\Handler\Mapping\MapByNamingConvention\ClassName\Suffix;
use PHPUnit\Framework\TestCase;

class SuffixTest extends TestCase
{
    /** @dataProvider examples */
    public function testCanAddSuffixToClassName(string $suffix, string $command, string $expectedResult) : void
    {
        self::assertSame(
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
