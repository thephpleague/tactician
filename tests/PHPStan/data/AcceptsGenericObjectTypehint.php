<?php
declare(strict_types=1);

namespace AcceptsExactParameterTypehintMatch;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle(object $command): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
