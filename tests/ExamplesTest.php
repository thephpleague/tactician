<?php

declare(strict_types=1);

namespace League\Tactician\Tests;

use PHPUnit\Framework\TestCase;

class ExamplesTest extends TestCase
{
    /**
     * @dataProvider exampleFiles
     * @param string[] $expect
     */
    public function testExample(string $file, array $expect) : void
    {
        // executes the example script using the php binary
        exec(
            implode(
                ' ',
                [
                    '/usr/bin/env',
                    'php',
                    '-f',
                    escapeshellarg(__DIR__ . "/../examples/{$file}")
                ]
            ),
            $output,
            $exitCode
        );

        self::assertSame(0, $exitCode);
        self::assertSame($expect, $output);
    }

    /**
     * @return array<int, array<int, array<int, string>|string>>
     */
    public function exampleFiles() : array
    {
        return [
            [
                '1-beginner-standard-usage.php',
                ['User alice@example.com was registered!']
            ],
            [
                '2-intermediate-create-middleware.php',
                [
                    'LOG: Starting RegisterUser',
                    'User alice@example.com was registered!',
                    'LOG: RegisterUser finished without errors'
                ]
            ],
            [
                '3-intermediate-custom-naming-conventions.php',
                ['See, Tactician now calls the handle method we prefer!']
            ],
            [
                '4-conditional-handlers.php',
                [
                    'Dispatched MyExternalCommand!',
                    'User alice@example.com was registered!'
                ]
            ]
        ];
    }
}
