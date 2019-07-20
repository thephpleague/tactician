<?php
declare(strict_types=1);

namespace League\Tactician\Tests\PHPStan;

use PHPStan\Testing\LevelsTestCase;

final class HandlerReturnTypeExtensionTest extends LevelsTestCase
{
    public function dataTopics(): array
    {
        return [
            ['IncorrectHandlerReturnType'],
            ['MissingHandlerClass'],
            ['MissingHandlerReturnType'],
            ['VoidReturnType'],
        ];
    }

    public function getDataPath(): string
    {
        return __DIR__.'/data';
    }

    public function getPhpStanExecutablePath(): string
    {
        return __DIR__.'/../../vendor/bin/phpstan';
    }

    public function getPhpStanConfigPath(): ?string
    {
        return __DIR__ . '/../../phpstan.neon';
    }
}
