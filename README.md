# Tactician

[![Travis CI](https://api.travis-ci.org/thephpleague/tactician.svg?branch=master)](https://travis-ci.org/thephpleague/tactician)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/build-status/master)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/thephpleague/tactician/blob/master/LICENSE)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c/small.png)](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c)

A small, pluggable command bus.

See the [full docs](http://tactician.thephpleague.com) or the examples directory to get started.

## Install

Using Composer:

`composer require league/tactician`

## Plugins
The core Tactician package is small but there are several plugin packages that extend the usefulness of Tactician:

- [Logger](https://github.com/thephpleague/tactician-logger): Adds PSR-3 logging support for receiving, completing or failing commands.
- [Doctrine](https://github.com/thephpleague/tactician-doctrine): Wraps commands in separate Doctrine ORM transactions.
- [and many more](https://packagist.org/search/?q=tactician)

## PHPStan Integration

Traditionally, command buses can obscure static analysis. The Tactician PHPStan plugin helps bring stronger type checking by finding missing handler classes, validating handler return types and more.

You'll need to make your `CommandToHandlerMapping` available to PHPStan. The easiest way to do this is to create a small bootstrap file that returns the same Handler configuration you use in your app. 

A simple version of this might look like:

~~~
# handler-mapper-loader.php
<?php

use League\Tactician\Handler\Mapping\ClassName\Suffix;
use League\Tactician\Handler\Mapping\MappingByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\Handle;

return new MappingByNamingConvention(
    new Suffix('Handler'),
    new Handle()
);
~~~

You can also your bootstrap container or anything else you like, you just need to return a `CommandToHandlerMapping`.

Now expose the bootstrap file in your `phpstan.neon` config. 

~~~
# phpstan.neon
parameters:
    tactician:
        bootstrap: handler-mapping-loader.php
~~~

And you're good to go!

## Framework Integration
There are a number of framework integration packages for Tactician, [search for Tactician on Packagist](https://packagist.org/search/?q=tactician) for the most up-to-date listings.

## Testing
To run all unit tests, use the locally installed PHPUnit:

~~~
$ ./vendor/bin/phpunit
~~~

## Security
Tactician has no previous security disclosures and due to the nature of the project is unlikely to. However, if you're concerned you've found a security sensitive issue in Tactician or one of its related projects, please email disclosures [at] rosstuck dot com.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
