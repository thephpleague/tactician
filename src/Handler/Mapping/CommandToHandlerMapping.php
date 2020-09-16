<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

interface CommandToHandlerMapping
{
    /**
     * @throws FailedToMapCommand
     */
    public function mapCommandToHandler(string $commandFQCN): MethodToCall;
}
