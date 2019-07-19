<?php
declare(strict_types=1);

namespace HandlerDoesNotTakeParameters;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
