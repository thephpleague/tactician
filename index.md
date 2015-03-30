---
layout: default
permalink: /
title: Tactician
---

# Introduction
[![Author](http://img.shields.io/badge/author-@rosstuck-blue.svg?style=flat-square)](https://twitter.com/rosstuck)
[![Source Code](http://img.shields.io/badge/source-league/tactician-blue.svg?style=flat-square)](https://github.com/thephpleague/tactician)
[![Travis CI](https://api.travis-ci.org/thephpleague/tactician.svg?branch=master)](https://travis-ci.org/thephpleague/tactician)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/build-status/master)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/thephpleague/tactician/blob/master/LICENSE)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c/small.png)](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c)

## What is Tactician?
Tactician is a command bus library. It tries to make using the command pattern in your application easy and flexible.

You can use Tactician for all types of command inputs but it especially targets service layers.
  
## What is a Command Bus?
The term is mostly used when we combine the [Command pattern](http://en.wikipedia.org/wiki/Command_pattern) with a [service layer](http://martinfowler.com/eaaCatalog/serviceLayer.html).
Its job is to take a Command object (which describes what the user wants to do) and match it to a Handler (which executes it). This can help structure your code neatly.

In practice, it looks like this:

~~~ php
// You build a simple message object like this:
class PurchaseProductCommand
{
    protected $productId;

    protected $userId;
    
    // ...and constructor to assign those properties...
}

// And a Handler class that expects it:
class PurchaseProductHandler
{
    public function handle(PurchaseProductCommand $command)
    {
        // use command to update your models, etc
    }
}

// And then in your controllers, you can fill in the command using your favorite
// form or serializer library, then drop it in the CommandBus and you're done!
$command = new PurchaseProductCommand(42, 29);
$commandBus->handle($command);
~~~

That's it. Tactician is the `$commandBus` part, doing all the plumbing of finding the handler and calling the right method. You know, the boring stuff. Commands can be any plain old PHP object and Handlers are usually other objects, but can be anything you'd like to configure.
 
One of the cool things about Tactician (and command buses in general) is that they're really easy to extend with new features by adding [middleware](/middleware).
Tactician aims to provide plugin packages that cover common tasks, like logging and database transactions. That way you don't have to put it in every handler and it immediately applies to your entire application.

## When should I use it?
Tactician is a great fit if you've got a service layer. If you're not sure what a service layer is, [Martin Fowler's PoEAA](http://www.amazon.com/Patterns-Enterprise-Application-Architecture-Martin/dp/0321127420) is a good starting point. 
Tactician's author also did a [talk on the subject](https://www.youtube.com/watch?v=ajhqScWECMo). 

Commands really help capture user intent. They're also a great stand-in for the models when it comes to forms or serializer libraries that expect getter/setter objects.

The command bus itself is really easy to decorate with extra behaviors, like locking or database transactions so it's very easy to extend with plugins.

Finally, if you're writing a standalone library that uses the command pattern internally, Tactician is really easy to tweak and can save you the hassle of building it yourself.

If any of that sounds familiar and helpful, Tactician might be right for you! :)

## When should I not use it?
If you've got a very small app that doesn't need a service layer, then Tactician won't offer much to you.

If you're already using a tool that provides a command bus (like [Broadway](https://github.com/qandidate-labs/broadway)), you're probably okay there too.

## Questions?
Tactician was created by Ross Tuck. Find him on Twitter at [@rosstuck](https://twitter.com/rosstuck).
