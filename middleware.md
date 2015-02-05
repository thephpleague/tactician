---
layout: default
permalink: /middleware/
title: Middleware
---

# What are Middleware?
Middleware are Tactician's plugins. They're a way to add behavior, like writing to a log, every time you execute a command.

If you've never seen a middleware system, the concept is easy: when you execute a command, it's is passed through every Middleware. Each Middleware is a separate object and do anything it wants.

Middleware are executed in sequence; the order is configured when you setup the StandardCommandBus and can't be changed later. However, each Middleware can control when the next link in the chain starts, by using a `$next` callable it receives as a parameter. This allows you to control if you're whether your custom behavior will come before or after command execution, or if you'll suppress the command from being executed at all.

It's easiest to see with an example, so we'll create a custom middleware that logs each command it receives.

## The Logger
We'll need a simple logger to use as an example. This one just prints to the screen, in real-life you'd use PSR-3.

~~~php
class Logger
{
    public function log($info)
    {
        echo "LOG: $info\n";
    }
}
~~~

## The Middleware
Now we need to implement a small middleware class to integrate the Logger with the CommandBus. 

~~~php
use League\Tactician\Middleware;

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
~~~

Some things to note:
- we can execute logic before _or_ after a Command is executed by changing where we call `$next($command)`.
- Tactician allows a Handler to return a value if we want, so we capture it here to return after we finish logging. This isn't required, but it makes it nicer for other folks.
- You don't have to pass the _same_ command to `$next` that came in! If you're working with a legacy system and want to convert or modify the incoming commands, you're free to do so. Just think carefully!


## Adding it to the Command Bus
Now that we have a working Middleware object, we give it to our CommandBus as a list of middleware. We'll also pass in the HandlerMiddleware which actually executes the commands in Handlers, otherwise our commands won't be executed.

~~~ php
$commandBus = new StandardCommandBus(
    [
        new LoggingMiddleware(new Logger()),
        $handlerMiddleware
    ]
);
~~~

Likewise, it's important to put them in a specific order. If you put the LoggingMiddleware after the HandlerMiddleware, the logging won't occur when you think it does. Also, the middlewares that actually execute Commands typically don't pass them onwards to `$next`, otherwise they run the risk of being executed twice (if there's another executing middleware further down the chain. 

When you're setting up your command bus, always consider the order you place the middleware, otherwise you can get strange effects. However, this also gives you a lot of flexability. For an example of this, see the page about the [LockingMiddleware plugin](/locking-middleware).


## Conclusion
So, this logging example is very simple but the Middleware concept is useful for lots of things! Imagine writing middleware for:

- Database transactions
- Validation
- Error handling
- Queuing 
- User permissions (can they send this command at all?)
- Anything you want!

Even better, middleware are really easy to unit test.

Tactician aims to ship with lots of Middleware plugins, so if you've got one you feel is interesting, send us a PR!
