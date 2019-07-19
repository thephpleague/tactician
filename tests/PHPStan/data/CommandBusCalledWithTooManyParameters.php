<?php
declare(strict_types=1);

namespace CommandBusCalledWithTooManyParameters;

use League\Tactician\CommandBus;

$commandBus = new CommandBus();
$commandBus->handle(new \stdClass(), true, false, true, 'again');
