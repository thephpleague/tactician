---
layout: default
permalink: /plugins/locking-command-bus/
title: Locking Command Bus
---

# Locking Command Bus
This plugin actually ships in the core Tactician "CommandBus" package, so it's available without installing any separate composer packages.

The Locking Bus prevents any Commands from starting execution until the current Command has completed. It doesn't throw exceptions, it just queues them in-memory.

It's very simple to setup:

~~~ php
use League\Tactician\CommandBus\LockingCommandBus;

// Just wrap the existing bus
$commandBus = new LockingCommandBus($commandBus);
~~~

The Locking Bus is very useful for controlling your transactional boundaries. Depending on where you stack it with other decorators, you can decide when they trigger: when the command was added or when it's actually executed. Both are useful.

For example, imagine this setup:

~~~ php
new LoggingCommandBus(
    new LockingCommandBus(
       new DatabaseTransactionCommandBus(
            $commandBus
        )
    )
)
~~~

By placing the DatabaseTransaction bus after the Locking Command Bus, we've ensured that each Command executes in a completely separate database transaction. However, if we wanted all commands to execute inside one big transaction, we could just move the DatabaseTransaction bus above the Lock.

Likewise, by putting the LoggingBus _before_ the LockingBus, we get the log entry at the initial point where the command is first added. This might be better for our debugging purposes then logging it at execution when several other commands have already run and presumably written to the log.

However, the important point is that by making the Locking behavior a separate decorator, you can tweak this behavior in anyway you want.
