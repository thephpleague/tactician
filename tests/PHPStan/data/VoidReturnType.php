<?php
declare(strict_types=1);

namespace VoidReturnType;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(SomeCommand $command): void
    {
    }
}

function run(CommandBus $bus): int
{
    return 1 + $bus->handle(new SomeCommand());
}
