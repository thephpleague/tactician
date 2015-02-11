<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

use League\Tactician\Middleware;
use League\Tactician\CommandBus;
use League\Tactician\Command;

/**
 * Let's say we want something to happen every time we execute a command.
 *
 * Maybe we start a database transaction or maybe it's just write something to
 * a debug log. We can do this easily by adding a Middleware.
 *
 * Middleware are Tactician's plugin system. If you've ever used an HTTP
 * middleware system, they're very similar.
 *
 * If not, the concept is easy: Every Command received is passed through every
 * Middleware, along with a $next callable. The middleware can do whatever task
 * it wants, it only needs to call $next($command) to start off the next
 * Middleware processing.
 */

// Let's build a simple logger example.
class Logger {
    public function log($info) { echo "LOG: $info\n"; }
}

// Our Logging Middleware. It accepts a logger in the constructor so it can
// use it later.
class LoggingMiddleware implements Middleware
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Command $command, callable $next)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");
        $returnValue = $next($command);
        $this->logger->log("$commandClass finished without errors");

        return $returnValue;
    }
}
// Some things to note:
//     - we can execute logic before _or_ after a Command is executed by
//       changing where we call $next($command).
//     - Tactician allows a Handler to return a value if we want, so we capture
//       it here to return after we finish logging. This isn't required, but it
//       makes it nicer for other folks.
//     - You don't have to pass the _same_ command to $next that came in! If
//       you're working with a legacy system and want to convert or modify the
//       incoming commands, you're free to do so. Just think carefully!

// Now that we have a working Middleware object, we give it to our CommandBus
// as a list of middleware. We'll also pass in the HandlerMiddleware we demoed
// in the previous example, otherwise our commands won't be executed!
$commandBus = new CommandBus(
    [
        new LoggingMiddleware(new Logger()),
        $handlerMiddleware
    ]
);

// Controller Code
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);

/**
 * So, logging is very simple but this Middleware concept is useful for lots of
 * things! Imagine writing middleware for:
 *      - database transactions
 *      - validation,
 *      - error handling
 *      - locking
 *      - user permissions (can they send this command at all?)
 *      - Anything you want!
 *
 * Even better, middleware are really easy to unit test!
 *
 * There's one final advantage: when we setup the middleware, we can control
 * the execution order. Want the logger to fire before db transaction? Sure,
 * just move it ahead in the middleware list when you create the CommandBus.
 */
