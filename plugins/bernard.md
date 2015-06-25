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

To setup queueing on the client side, simply pass the QueueMiddleware to the Command Bus. After that you queue commands by implementing the `League\Tactician\Bernard\QueueableCommand` interface (Alternatively you can implement `Bernard\Message`, see explanation later). Others will be passed to the next middleware in the chain.

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

// Create the command bus that receives the queued commands
$commandBus = new CommandBus([/* middlewares go here */]);

// Wire the command bus into Bernard's routing system
$receiver = new SingleBusReceiver($commandBus);
$router = new SimpleRouter();
$router->add('League\Tactician\Bernard\QueueableCommand', $receiver);

// Finally, create the Bernard consumer that runs through the pending queue
$consumer = new Consumer($router, new EventDispatcher());
$consumer->consume($queue);
~~~

The plugin tries to follow Bernard's logic as close as possible. To learn more about how consuming and routers work, check the [official documentation](http://bernardphp.com) for details.


#### Receivers

Receiver is a term used in both command pattern and the message terminology. In this case a receiver is a callable passed to the Router. The Router routes all messages to a receiver (or returns with error if no receiver is registered for a messsage). There are two receivers implemented by this plugin:

- `SingleBusReceiver`: This receiver should be used when the same command bus is used for the producer and the consumer side. It prevents a command from being requeued (causing an infinite loop).
- `SeparateBusReceiver`: Use this receiver in any other cases.


### `QueueableCommand` and `Message` interface

Bernard provides an interface for messages to implement. If you implement only this interface, it is totally fine. The reason we have our own interface for queueable commands is to ease the separation of commands sent by Tactician and other messages received by the consumer (so you can register a separate receiver for commands sent by Tactician). In theory, you can use your consumer with not just Tactician, but with any custom logic.
