# Tactician

[![Travis CI](https://api.travis-ci.org/thephpleague/tactician.svg?branch=master)](https://travis-ci.org/thephpleague/tactician)
[![Build Status](https://img.shields.io/github/workflow/status/thephpleague/tactician/CI/master)](https://github.com/thephpleague/tactician/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/league/tactician.svg)](https://packagist.org/packages/league/tactician)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/thephpleague/tactician/blob/master/LICENSE)

A small, flexible command bus.

*IMPORTANT: You're looking at the unreleased Tactician 2.0 code. See the [original docs](http://tactician.thephpleague.com) for info about 1.x versions.*

See the [full docs](http://tactician.thephpleague.com) or the examples directory to get started.

## Install

Using Composer:

`composer require league/tactician`

## Plugins
The core Tactician package is small but there are several plugin packages that extend the usefulness of Tactician:

- [PHPStan](https://github.com/thephpleague/tactician-phpstan): Add static analysis support to Tactician. Highly recommended.
- [Logger](https://github.com/thephpleague/tactician-logger): Adds PSR-3 logging support for receiving, completing or failing commands.
- [Doctrine](https://github.com/thephpleague/tactician-doctrine): Wraps commands in separate Doctrine ORM transactions.
- [and many more](https://packagist.org/search/?q=tactician)

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
