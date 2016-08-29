---
layout: default
permalink: /plugins/logger/
title: Logger
---

# Logger

[![Author](https://img.shields.io/badge/author-@rosstuck-blue.svg?style=flat-square)](https://twitter.com/rosstuck)
[![Source](https://img.shields.io/badge/source-league/tactician--logger-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician-logger)
[![Packagist](https://img.shields.io/packagist/v/league/tactician-logger.svg?style=flat-square)](https://packagist.org/packages/league/tactician-logger)

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
- Command handled: the command has been executed without any exceptions
- Command failed: an exception was raised during execution

## Formatters
The middleware is only responsible for determining which "event" happened. A Formatter is responsible for actually writing a message to the logger.

This package ships with a couple of default Formatters. Assuming we dispatched a `RegisterUserCommand`, you can see their log messages and PSR-3 contexts below ([PSR-3 Contexts](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#13-context) are arrays of extra, structured data you can send with the log message. Useful if you're using GELF, for example).

Formatter                  | Message
---------------------------|---------------------------------------| Context
`ClassNameFormatter`       | Command received: RegisterUserCommand | _none_
`ClassPropertiesFormatter` | Command received: RegisterUserCommand | {"email": "alice@example.org", password:"s3krit" }

It's worth noting the `ClassPropertiesFormatter` extracts the command's properties, regardless of their visibility. However, it uses a very naive serializer and does not recurse into sub-objects or arrays, it merely shows their type. It also has no speed optimizations and is not recommended for production use. You can write an adapter for, say, [JMS Serializer](http://jmsyst.com/libs/serializer) to make it more performant but please consider the security implications of logging raw command data (i.e. user passwords) before doing this.

## Custom Formatters
Creating your own Formatter is very simple, you only need to implement the `Formatter` interface and return the string you'd like logged. If you'd prefer to not log any message for a particular event (say, nothing when a command is received but only when it's completed) then you can just leave that method as an empty implementation.

~~~php
use League\Tactician\Logger\Formatter\Formatter;
use Psr\Log\LoggerInterface;

class CatFormatter implements Formatter
{
    public function logCommandReceived(LoggerInterface $logger, $command)
    {
        $logger->debug('Meow, command received! =^._.^=');
    }

    public function logCommandSucceeded(LoggerInterface $logger, Command $command, $returnValue)
    {
        $logger->debug('Meow, command done! (=^･^=)');
    }

    public function logCommandFailed(LoggerInterface $logger, Command $command, Exception $e)
    {
        $reason = $e->getMessage();
        $logger->critical("Meow, command failed because $reason ^-.-^");
    }
}
~~~

At a glance, it might seem like a strange interface: why pass the logger? Why not just return the string? Well, we did that in earlier versions but it turns out there's a lot more to logs than just the messages. Folks wanted to be able to conditionally change the log level (X command is error, while Y command is critical) or they wanted to put extra information in the log context. The simplest way to support these use cases was to provide direct access to the log object, so now we pass it directly. Tweak it to your heart's content!

## Change Logger Levels
PSR-3 supports [log levels](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#5-psrlogloglevel) which allow you to filter messages by their importance. By default, Tactician defaults to the Debug level for commands being received and handled but the Error level for commands failing due to exceptions.

If you'd like to change these levels, you can optionally pass them as constructor arguments to the Formatter of your choice.

~~~php
use Psr\Log\LogLevel; 

new ClassNameFormatter(
    LogLevel::INFO,     // Command Received
    LogLevel::INFO,     // Command Completed
    LogLevel::CRITICAL  // Command Failed
);
~~~

(Do note the ClassPropertiesFormatter has additional arguments).

We allow setting these as a convenience so you don't have to implement your own formatter just to tweak the log levels. You don't have to do this when implementing your own formatter or logger middleware.

## Logging Custom Needs
Between the various PSR-3 plugins and Formatters available, Tactician tries to provide a reasonably flexible starting point. However, if really need something more specific, there's nothing wrong with creating your own logging middleware. We encourage it!

The [Middleware intro tutorial](/middleware) demonstrates how to build your own Logger middleware from the ground up.
