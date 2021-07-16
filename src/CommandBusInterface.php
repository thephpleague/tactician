<?php

declare(strict_types=1);

namespace League\Tactician;

interface CommandBusInterface
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @return mixed
     */
    public function handle(object $command);
}
