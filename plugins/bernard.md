---
layout: default
permalink: /plugins/bernard/
title: Bernard
---

# Bernard

[![Author](http://img.shields.io/badge/author-@sagikazarmark-blue.svg?style=flat-square)](https://twitter.com/sagikazarmark)
[![Source](http://img.shields.io/badge/source-league/tactician--bernard-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician-bernard)
[![Packagist](http://img.shields.io/packagist/v/league/tactician-bernard.svg?style=flat-square)](https://packagist.org/packages/league/tactician-bernard)


This plugin provides you tools for both sending commands to and consuming from a queue.


### Remote execution

To send a command to a queue, simply pass the middleware to the Command Bus. Currently only commands implementing `League\Tactician\Bernard\QueueableCommand` can be sent to the queue, others will be passed to the next middleware in the chain.

~~~ php
use League\Tactician\Bernard\QueueMiddleware;
use League\Tactician\CommandBus;

$queueMiddleware = new QueueMiddleware($queue);

$commandBus = new CommandBus([$queueMiddleware]);
$commandBus->handle($command);
~~~

The `$queue` variable in the example is a `Bernard\Queue` instance. See the [official documentation](http://bernardphp.com) for details.


### Consuming commands

On the other side of the message queue you must set up a consumer:

~~~ php
use Bernard\Consumer;
use League\Tactician\Bernard\Router;
use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventDispatcher;

// inject some middlewares
$commandBus = new CommandBus([]);

$router = new Router($commandBus);

$consumer = new Consumer($router, new EventDispatcher());

$consumer->consume($queue);
~~~
