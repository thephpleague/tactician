<?php
declare(strict_types=1);

namespace AcceptsSuperclassAsParameterTypeHint;

use League\Tactician\CommandBus;

class OriginalParentCommand
{

}

class SomeCommand extends OriginalParentCommand
{

}

class SomeCommandHandler
{
    public function handle(OriginalParentCommand $command): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
