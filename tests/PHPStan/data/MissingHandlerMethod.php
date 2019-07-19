<?php
declare(strict_types=1);

namespace MissingHandlerMethod;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class SomeCommandHandler
{
    public function derp(): void
    {
    }
}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
