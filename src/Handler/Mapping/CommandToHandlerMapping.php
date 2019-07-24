<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

interface CommandToHandlerMapping
{
    /**
     * @throws FailedToMapCommand
     */
    public function getClassName(string $commandClassName): string;

    /**
     * @throws FailedToMapCommand
     */
    public function getMethodName(string $commandClassName): string;
}
