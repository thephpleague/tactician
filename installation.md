---
layout: default
permalink: /installation/
title: Installation
---

# Installation

Tactician is available on Packagist.

To install, just run:

    > composer require league/tactician

Now we need to configure Tactician for your project.

## Setup
There are a few different ways to configure Tactician, depending on your Framework.

- [No Framework](#no-framework)
- [Symfony2](#symfony2)
- [Zend Framework 2](#zend-framework-2)
- [Laravel](#laravel)

## No Framework

If you just want to get started and don't care about tweaking anything, you can use our DefaultSetup factory to get running with a minimum of fuss.

~~~ php
// All you need to do is pass an array mapping your command class names to
// your handler instances. Everything else is already setup.
League\Tactician\Setup\QuickStart::create(
    [
        AddTaskCommand::class      => $someHandler
        CompleteTaskCommand::class => $someOtherHandler
    ]
);

// The only rule is that your handlers must have a "handle" method
class AddTaskHandler
{
    public function handle(AddTaskCommand $command) {
        // ...
    }
}
~~~

That said, if you'd like to change the handler method called or any other options, take a look at the [Tweaking Tactician](/tweaking-tactician) page for more details. It's really easy. Promise.

## Symfony2
Sorry, a bundle is on our roadmap but we're short-handed. If you'd like to help out, [please get in touch](https://github.com/thephpleague/tactician/issues)!

## Zend Framework 2
Gary Hockin ([@GeeH](https://twitter.com/GeeH)) is already working on it! If you'd like to help out, [you can find the repo here](https://github.com/GeeH/TacticianModule)!

## Laravel
Sorry, a provider is on our roadmap but we're short-handed. If you'd like to help out, [please get in touch](https://github.com/thephpleague/tactician/issues)!
