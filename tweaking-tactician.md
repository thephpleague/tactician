---
layout: default
permalink: /tweaking-tactician/
title: Tweaking Tactician
---

# Tweaking Tactician
Tactician is really flexible, you can change almost anything about it. This page walks you through setting up a Command Bus that executes Commands by passing them to a matching Handler.

Along the way, we'll show you the important parts of Tactician so you can tweak them yourself.

## 1. Handler Method
First, what method do you want to call on your Handlers when they receive a command? This is determined by the [`MethodNameInflector` classes](https://github.com/thephpleague/tactician/tree/master/src/Handler/MethodNameInflector). 

Assuming we have a Command class called `RentMovieCommand`, this table shows you what each inflector does: 

Handler Method             | Class to use                                | Notes
---------------------------|---------------------------------------------|-----------------------------------------------
`handle()`                 | **`HandleInflector`**                       | Easy to read, easy to guess
`handleRentMovieCommand()` | **`HandleClassNameInflector`**              | Lets you handle multiple commands on the same class
`handleRentMovie()`        | **`HandleClassNameWithoutSuffixInflector`** | Same as HandleClassName but shaves any -Command suffix off your class
`__invoke()`               | **`InvokeInflector`**                       | Good for invokable classes or closures.

If you'd like to do something custom here, just implement the [`MethodNameInflector` interface](https://github.com/thephpleague/tactician/blob/master/src/Handler/MethodNameInflector/MethodNameInflector.php). All you need to do is return the string name of the method to call. This inflector will also receive the command and handler so you can use Reflection on them if you want.

## 2. Command Naming
In order to load a Handler, we need to identify which Command we're dealing with. That's the job of the CommandNameExtractor classes: they accept an incoming command and then return a string name for it we can use to identify the command.

Most of the time, this is just the Command's class name but Tactician ships with a couple of different strategies:

CommandNameExtractor    | Notes
------------------------|---------------------------------------------------
`ClassNameExtractor`    | The simplest name extractor, just returns the class name of the Command.
`NamedCommandExtractor` | Expects each Command to implement the `NamedCommand` interface, which provides the command name directly.

## 3. Loading Your Handlers
Now that we've got a Command's name, we can use it to load the actual Handler class. 

The actual loading is done by the [`HandlerLocator` classes](https://github.com/thephpleague/tactician/tree/master/src/Handler/Locator). If you're using a Tactician framework module/bundle/provider, you should probably use the custom loader for that framework, but any locator will work.

The complete list of options are:

Locator           | Notes
------------------|---------------------------------------------------
[In Memory](https://github.com/thephpleague/tactician/blob/master/src/Handler/Locator/InMemoryLocator.php) | Maps Command Name to an existing Handler instance.
[Callable](https://github.com/thephpleague/tactician/blob/master/src/Handler/Locator/CallableLocator.php) | Loads Commands from a callable (closure or ```[$object, 'method']```) 
[League Container](https://github.com/thephpleague/tactician-container) | Maps Command Name to a League\Container instance.
[Symfony Container](https://github.com/thephpleague/tactician-bundle) | Maps Command Name to Symfony service container tags

Configuring the Locator will vary depending on which Locator you use. Some will work with DI containers, some just use plain PHP arrays, so check the class itself.

If you'd like to wrap a DI container and there isn't an existing adapter, the easiest solution is to use the CallableLocator and pass it a callable like ```[$container, 'get']```. 

You can also implement a custom Locators by implementing the [HandlerLocator interface](https://github.com/thephpleague/tactician/blob/master/src/Handler/Locator/HandlerLocator.php). This is just a single method that receives the Command Name as a string and returns the appropriate Handler.

## 4. Creating the Command Bus
Now that you've chosen a Locator, NameExtractor and MethodNameInflector, you need to pass them to the Command Bus for execution. In Tactician, there's one "core" command bus that you should always use: the CommandBus.

On its own, the CommandBus doesn't do much. It accepts a list of Middleware and passes the Command through each of them. Really, the CommandBus is just a place to hang all of the Middleware plugins together in a consistent way.

~~~ php
use League\Tactician\CommandBus;

// This command bus receives an empty list of middleware and does nothing.
$commandBus = new CommandBus([]);
~~~

To actually process the commands, we need to add Middleware.

The most important piece of Middleware is the [CommandHandlerMiddleware](https://github.com/thephpleague/tactician/blob/master/src/Handler/CommandHandlerMiddleware.php). This class uses the Handler and MethodNameInflector we created above to find the right Handler and execute it.

Pass that configured Middleware to your CommandBus and you're ready to rock.

~~~ php
// Choose our method name
$inflector = new HandleClassNameInflector();

// Choose our locator and register our command
$locator = new InMemoryLocator();
$locator->addHandler(new RentMovieHandler(), RentMovieCommand::class);

// Choose our Handler naming strategy
$nameExtractor = new ClassNameExtractor();

// Create the middleware that executes commands with Handlers
$commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

// Create the command bus, with a list of middleware
$commandBus = new CommandBus([$commandHandlerMiddleware]);
~~~

## 5. Other Middleware
Now that you've got the core Command Bus functionality up and running, you can customize it further by adding other middleware that change the behavior.

For example, we recommend using the LockingMiddleware, since that will prevent one command from being executed while another is already running.

Building on our previous example:

~~~ php

$commandBus = new CommandBus(
    [
        new LockingMiddleware(),
        new CommandHandlerMiddleware($locator, $inflector)
    ]
);
~~~

You can create your own custom Middleware by implementing the [Middleware interface](https://github.com/thephpleague/tactician/blob/master/src/Middleware.php).

Tactician aims to ship with lots of useful decorators, you can find a complete list in the menu.

### And more
If you've read this far down but you're still curious, take a look at the [examples directory on Github](https://github.com/thephpleague/tactician/tree/master/examples).

Also, if you've implemented something custom for Tactician, from Inflectors to Middleware, please send us a pull request so we can share it with other Tactician users. We'd really appreciate it.
