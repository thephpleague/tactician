<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

use League\Tactician\CommandBus;
use League\Tactician\Command;

/**
 * Let's say we want something happen every time we execute a command.
 *
 * Maybe we start a database transaction or maybe it's just write something to
 * a debug log. We can do this easily by writing a decorator around our main
 * command bus.
 */

// Very simple logger example.
class Logger {
    public function log($info) { echo "LOG: $info\n"; }
}

// Our Logging CommandBus. It takes the "main" command bus in the constructor
// and passes the command onto it for execution, while it only cares about
// doing the logging part.
class LoggingCommandBus implements CommandBus
{
    protected $innerCommandBus;

    protected $logger;

    public function __construct(CommandBus $innerCommandBus, Logger $logger)
    {
        $this->innerCommandBus = $innerCommandBus;
        $this->logger = $logger;
    }

    public function execute(Command $command)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");
        $returnValue = $this->innerCommandBus->execute($command);
        $this->logger->log("$commandClass finished without errors");

        return $returnValue;
    }
}

// Now we wrap the main command bus in our new logging command bus and
// all of our Command operations in our app are instantly logged, hooray!
$commandBus = new LoggingCommandBus($commandBus, new Logger());

// Controller Code
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);

/**
 * So, logging is very simple but this decorator concept is useful for lots of
 * things! Imagine writing decorators for:
 *      - database transactions
 *      - validation,
 *      - error handling
 *      - locking
 *      - user permissions (can they send this command at all?)
 *      - Anything you want!
 *
 * Even better, decorators are really easy to unit test!
 *
 * There's one final advantage: when we setup the decorators, we can control
 * the execution order. Want the logger to fire before db transaction? Sure,
 * just move it ahead in the decorator stack.
 */
