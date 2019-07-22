<?php
declare(strict_types=1);

namespace League\Tactician\PHPStan;

use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
use PHPStan\ShouldNotHappenException;

final class MappingLoader
{
    public static function loadBootstrapFile(string $filename): CommandToHandlerMapping
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new ShouldNotHappenException('Tactician-PHPStan bootstrap file could not be located.');
        }

        return require $filename;
    }
}
