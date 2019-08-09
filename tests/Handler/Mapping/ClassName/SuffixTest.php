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
        self::assertSame(
            $expectedResult,
            (new Suffix($suffix))->getClassName($command)
        );
    }

    public function testBuildHandlerSuffix(): void
    {
        $result = Suffix::handler()->getClassName('MyCommand');

        self::assertSame('MyCommandHandler', $result);
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
