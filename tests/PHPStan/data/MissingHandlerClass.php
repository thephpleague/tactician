<?php
declare(strict_types=1);

namespace MissingHandlerClass;

use League\Tactician\CommandBus;

class SomeCommand
{

}

$commandBus = new CommandBus();
$commandBus->handle(new SomeCommand());
