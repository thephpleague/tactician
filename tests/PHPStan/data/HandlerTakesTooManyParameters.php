<?php
declare(strict_types=1);

namespace HandlerTakesTooManyParameters;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(SomeCommand $command, bool $otherStuff): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
