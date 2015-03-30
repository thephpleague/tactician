---
layout: default
permalink: /plugins/league-container/
title: League\Container
---

# League\Container

[![Author](http://img.shields.io/badge/author-@NigelGreenway-blue.svg?style=flat-square)](https://twitter.com/nigelgreenway)
[![Source](http://img.shields.io/badge/source-league/tactician--container-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician-container)
[![Packagist](http://img.shields.io/packagist/v/league/tactician-container.svg?style=flat-square)](https://packagist.org/packages/league/tactician-container)

This plugin adds support for lazy-loading Command Handlers from [League\Container](http://container.thephpleague.com/). If you're using Container in your project, this plugin will likely improve your memory usage and startup times compared to the InMemoryHandlerLocator that Tactician ships with by default.
  
Setup has a few steps but it isn't very complicated:

~~~php
// Map your command classes to the container id of your handler. When using
// League\Container, the container id is typically the class or interface name
$commandToHandlerMap = [
    RentMovieCommand::class => RentMovieHandler::class
];

// Next we create a new Tactician ContainerLocator, passing in both the map and
// a fully configured League\Container instance.
use League\Tactician\Container\ContainerLocator;
$containerLocator = new ContainerLocator(
    $leagueContainer, 
    $mapping
);

// Finally, we pass the ContainerLocator into the CommandHandlerMiddleware that
// we use in almost every CommandBus.
$commandHandlerMiddleware = new CommandHandlerMiddleware(
    $containerLocator,
    new HandleInflector()
)

// And that's it! Drop it in our command bus and away you go.
$commandBus = new CommandBus(
    [
        // your other middleware...
        $commandHandlerMiddleware    
    ]
);
~~~
