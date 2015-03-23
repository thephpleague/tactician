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

To send a command to a queue, simply pass the middleware to the Command Bus. You can implement `League\Tactician\Bernard\QueueableCommand` to mark a command as queueable. (Alternatively you can implement both `Bernard\Message` and `League\Tactician\Command`) Others will be passed to the next middleware in the chain.

~~~ php
use Bernard\Producer;
use League\Tactician\Bernard\QueueMiddleware;
use League\Tactician\CommandBus;

// $producer = new Producer(/*...*/);

$queueMiddleware = new QueueMiddleware($producer);

$commandBus = new CommandBus([$queueMiddleware]);
$commandBus->handle($command);
~~~

The `$producer` variable in the example is a `Bernard\Producer` instance. See the [official documentation](http://bernardphp.com) for details.


### Consuming commands

On the other side of the message queue you must set up a consumer:

~~~ php
use Bernard\Consumer;
use Bernard\Router\SimpleRouter;
use League\Tactician\Bernard\Receiver\SingleBusReceiver;
use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventDispatcher;

// inject some middlewares
$commandBus = new CommandBus([]);

$receiver = new SingleBusReceiver($commandBus);

$router = new SimpleRouter();

$router->add('League\Tactician\Command', $receiver);

$consumer = new Consumer($router, new EventDispatcher());

$consumer->consume($queue);
~~~

The plugin tries to follow Bernard's logic as close as possible. To leard more about how consuming and routers work, check the [official documentation](http://bernardphp.com) for details.

#### Receivers

Receiver is a term used in both command pattern and the message terminology. In this case a recevier is a callable passed to the Router. The Router routes all messages to a receiver (or returns with error if no receiver is registered for a messsage). There are two receivers implemented by this plugin:

- `SingleBusReceiver`: This receiver should be used when the same command bus is used for the producer and the consumer side. It prevents a command from being requeued (causing an infinite loop).
- `SeparateBusReceiver`: Use this receiver in any other cases.
