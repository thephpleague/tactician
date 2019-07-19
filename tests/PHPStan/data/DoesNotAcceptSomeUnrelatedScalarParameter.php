<?php
declare(strict_types=1);

namespace DoesNotAcceptSomeUnrelatedScalarParameter;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(bool $command): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
