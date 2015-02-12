---
layout: default
permalink: /plugins/locking-middleware/
title: Locking Middleware
---

# Locking Middleware

Composer | [league/tactician](https://packagist.org/packages/league/tactician)
Github | [thephpleague/tactician](https://github.com/thephpleague/tactician)
Authors | [Ross Tuck](http://twitter.com/rosstuck)

This plugin actually ships in the core Tactician "CommandBus" package, so it's available without installing any separate composer packages.

The LockingMiddleware blocks any Commands from running inside Commands. If a Command is already being executed and another Command comes in, this plugin will queue it in-memory until the first Command completes.

It's very simple to setup:

~~~ php
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\CommandBus;

$lockingMiddleware = new LockingMiddleware();

$commandBus = new CommandBus(
    [
        $lockingMiddleware,
        // ... your other middleware...
    ]
);
~~~

The LockingMiddleware is very useful for controlling your transactional boundaries. Depending on where you stack it with your other middleware, you can decide when they trigger: when the command was added or when it's actually executed. Both are useful.

For example, imagine this setup:

~~~ php
new CommandBus(
    [
        $loggingMiddleware,
        $lockingMiddleware,
        $databaseTransactionMiddleware,
        $commandHandlerMiddleware
    ]
);
~~~

By placing the DatabaseTransaction after the Locking, we've ensured that each Command executes in a completely separate database transaction. However, if we wanted all commands to execute inside one big transaction, we could just move the DatabaseTransaction above the Locking.

Likewise, by putting the Logging _before_ the Locking, we get the log entry at the initial point where the command is first added. This might be better for our debugging purposes then logging it at execution when several other commands have already run and presumably written to the log.

However, the important point is that by making the Locking behavior a separate decorator, you can tweak this behavior in anyway you want.
