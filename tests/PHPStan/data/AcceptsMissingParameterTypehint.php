<?php
declare(strict_types=1);

namespace AcceptsMissingParameterTypehint;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function handle($command): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
