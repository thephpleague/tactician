---
layout: default
permalink: /plugins/logger/
title: Logger
---

# Logger

[![Author](http://img.shields.io/badge/author-@rosstuck-blue.svg?style=flat-square)](https://twitter.com/rosstuck)
[![Source](http://img.shields.io/badge/source-league/tactician--logger-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician-logger)
[![Packagist](http://img.shields.io/packagist/v/league/tactician-logger.svg?style=flat-square)](https://packagist.org/packages/league/tactician-logger)

During development, logging can help visualize the flow commands take in the system. The `league\tactician-logger` package provides out-of-the-box support for logging command data to any [PSR-3 compliant logger](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) (so, basically all of them). 

To get started, create the LoggerMiddleware and pass it a Formatter and your PSR-3 logger. 

~~~php

$commandBus = new League\Tactician\CommandBus(
    [
        new LoggerMiddleware($formatter, $logger),
        // ...
    ]
);
~~~

The LoggerMiddleware records three possible events:

- Command received: the command has been received but not yet executed
- Command completed: the command has been executed without any exceptions
- Command failed: an exception was raised during execution

## Formatters
A Formatter is responsible for converting the current command into a message that's appended to the log. This can be a slightly different message for each event.  

This package ships with a couple of default Formatters. Assuming we dispatched a `RegisterUserCommand`, you can see their output below.

Formatter                  | Log entry
---------------------------|--------------------------------
`ClassNameFormatter`       | Command received: RegisterUserCommand
`ClassPropertiesFormatter` | Command received: RegisterUserCommand {"email": "alice@example.org", password:"s3krit" }

It's worth noting the `ClassPropertiesFormatter` outputs a small JSON blob containing the command's properties, regardless of their visibility. However, it uses a very naive serializer and does not recurse into sub-objects or arrays, it merely shows their type. It also has no speed optimizations and is not recommended for production use.

## Custom Formatters
Creating your own Formatter is very simple, you only need to implement the `Formatter` interface and return the string you'd like logged. If you'd prefer to not log any message for a particular event (say, nothing when a command is received but only when it's completed) then you can just return null and the `LoggerMiddleware` will skip over that message.

~~~php
use League\Tactician\Command;
use League\Tactician\Logger\Formatter\Formatter;

class CatFormatter implements Formatter
{
    public function commandReceived(Command $command)
    {
        return 'Meow, command received! =^._.^=';
    }

    public function commandCompleted(Command $command)
    {
        return 'Meow, command done! (=^･^=)';
    }

    public function commandFailed(Command $command, Exception $e)
    {
        $reason = $e->getMessage();
        return "Meow, command failed because $reason ^-.-^";
    }
}
~~~

## Change Logger Levels
PSR-3 supports [log levels](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#5-psrlogloglevel) which allow you to filter messages by their importance. By default, the `LoggerMiddleware` uses the Debug level for commands being received and completed but the Error level for commands failing due to exceptions.

If you'd like to change these levels, you can override them in the `LoggerMiddleware` constructor:

~~~php
use Psr\Log\LogLevel; 

new LoggerMiddleware(
    $formatter,
    $logger
    LogLevel::INFO,     // Command Received
    LogLevel::INFO,     // Command Completed
    LogLevel::CRITICAL  // Command Failed
);
~~~

## Logging Custom Needs
Between the various PSR-3 plugins and Formatters available, Tactician tries to provide a reasonably flexible starting point. However, if really need something more specific, there's nothing wrong with creating your own logging middleware. The [Middleware intro tutorial](/middleware) demonstrates how to build your own Logger middleware from the ground up.  
