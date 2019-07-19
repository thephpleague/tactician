<?php
declare(strict_types=1);

namespace MissingHandlerReturnType;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(SomeCommand $command)
    {
    }
}

function run(CommandBus $bus): int
{
    return $bus->handle(new SomeCommand());
}
