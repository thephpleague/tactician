<?php
declare(strict_types=1);

namespace IncorrectHandlerReturnType;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(SomeCommand $command): string
    {
        return 'derp';
    }
}

function run(CommandBus $bus)
{
    array_values(
        $bus->handle(new SomeCommand())
    );
}
