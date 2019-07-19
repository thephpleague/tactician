<?php
declare(strict_types=1);

namespace DoesNotAcceptSubclassAsParameterTypeHint;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeNarrowerCommand extends SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(SomeNarrowerCommand $command): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
