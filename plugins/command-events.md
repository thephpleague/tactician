---
layout: default
permalink: /plugins/command-events/
title: Command Events
---

# Command Events

[![Author](https://img.shields.io/badge/author-@sagikazarmark-blue.svg?style=flat-square)](https://twitter.com/sagikazarmark)
[![Source](https://img.shields.io/badge/source-league/tactician--command--events-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician-command-events)
[![Packagist](https://img.shields.io/packagist/v/league/tactician-command-events.svg?style=flat-square)](https://packagist.org/packages/league/tactician-command-events)

This plugin lets you listen to some events emitted during command execution:

- `command.received`: Emitted when a command is received by the command bus
- `command.handled`: Emitted when a command is handled without errors
- `command.failed`: Emitted when an error occurred during command execution

Setup is simple:

~~~ php
use League\Tactician\CommandBus;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\CommandEvents\Event\CommandHandled;

$eventMiddleware = new EventMiddleware();

// type-hint is optional
$eventMiddleware->addListener(
	'command.handled',
	function (CommandHandled $event) {
		// log the success
	}
);

$commandBus = new CommandBus([$eventMiddleware]);
$commandBus->handle($command);
~~~

Optionally you can inject an event emitter into the middleware:

~~~ php
use League\Event\Emitter;
use League\Tactician\CommandEvents\EventMiddleware;

$emitter = new Emitter();
$eventMiddleware = new EventMiddleware($emitter);

// other possible solution
// $eventMiddleware->setEmitter($emitter);
~~~

You can also catch an error and prevent it from causing the application to fail:

~~~ php
use League\Tactician\CommandEvents\Event\CommandFailed;

$eventMiddleware->addListener(
	'command.failed',
	function (CommandFailed $event) {
		// log the failure
		$event->catchException(); // without calling this the exception will be thrown
	}
);

// something bad happens, exception thrown
$commandBus->handle($command);
~~~
