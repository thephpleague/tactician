<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

/**
 * Tactician is meant to be very customizable, it makes few assumptions about
 * your code.
 *
 * For example, let's say you like to handle command X in one way and
 * command Y in another.
 *
 * We can write a custom middleware for that.
 */

use League\Tactician\CommandBus;
use League\Tactician\Middleware;

// External command
interface ExternalCommand
{
}

// We create some custom middleware that will forward any ExternalCommand to
// a separate CommandBus (or HTTP endpoint or service or whatever you want).
final class ExternalCommandMiddleware implements Middleware
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function execute(object $command, callable $next): mixed
    {
        if ($command instanceof ExternalCommand) {
            return $this->commandBus->handle($command);
        }
        return $next($command);
    }
}

// and we'll create a custom command handler/middleware
final class ExternalCommandHandler implements Middleware
{
    public function execute(object $command, callable $next): mixed
    {
        echo \sprintf("Dispatched %s!\n", \get_class($command));
    }
}

// and we'll create our example command
class MyExternalCommand implements ExternalCommand
{
}

// Now  let's instantiate our ExternalCommandHandler, CommandBus and ExternalCommandMiddleware
$externalCommandHandler = new ExternalCommandHandler();
$externalCommandBus = new CommandBus($externalCommandHandler);
$externalMiddleware = new ExternalCommandMiddleware($externalCommandBus);

// Time to create our main CommandBus
$commandBus = new CommandBus($externalMiddleware, $handlerMiddleware);

// Controller Code time!
$externalCommand = new MyExternalCommand();

$commandBus->handle($externalCommand);

$command = new RegisterUser();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);
