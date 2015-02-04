<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

/**
 * Tactician is meant to be very customizable, it makes no assumptions about
 * your code.
 *
 * For example, let's say you like to handle command X in one way and
 * command Y in another.
 *
 * We can write a custom middleware for that.
 */

use League\Tactician\Command;
use League\Tactician\CommandBus;
use League\Tactician\Middleware;

// External command
interface ExternalCommand extends Command
{
}

// We create some custom middleware that will forward any ExternalCommand to
// a separate CommandBus (or HTTP endpoint or queue).
final class ExternalCommandMiddleware implements Middleware
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function execute(Command $command, callable $next)
    {
        if ($command instanceof ExternalCommand) {
            return $this->commandBus->execute($command);
        }
        return $next($command);
    }
}

// and we'll create a custom command handler/middleware
final class ExternalCommandHandler implements Middleware
{
    public function execute(Command $command, callable $next)
    {
        echo sprintf("Dispatched %s!\n", get_class($command));
    }
}

// and we'll create our example command
class MyExternalCommand implements ExternalCommand
{
}

// Now  let's instantiate our ExternalCommandHandler, CommandBus and ExternalCommandMiddleware
$externalCommandHandler = new ExternalCommandHandler();
$externalCommandBus = new CommandBus([$externalCommandHandler]);
$externalMiddleware = new ExternalCommandMiddleware($externalCommandBus);

// Time to create our main CommandBus
$commandBus = new CommandBus([$externalMiddleware, $handlerMiddleware]);

// Controller Code time!
$externalCommand = new MyExternalCommand();

$commandBus->execute($externalCommand);

$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);
