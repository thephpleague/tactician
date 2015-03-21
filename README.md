# Tactician

[![Travis CI](https://api.travis-ci.org/thephpleague/tactician.svg?branch=master)](https://travis-ci.org/thephpleague/tactician)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/thephpleague/tactician/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/tactician/build-status/master)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/thephpleague/tactician/blob/master/LICENSE)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c/small.png)](https://insight.sensiolabs.com/projects/54275a78-bc70-4bb3-9ac4-4eee700c6a1c)

A small, pluggable command bus. Still in active development.

See the [full docs](http://tactician.thephpleague.com) or the examples directory to get started.

## Plugins
The core Tactician package is small but there are several plugin packages that extend the usefulness of Tactician:

- [Logger](https://github.com/thephpleague/tactician-logger): Adds PSR-3 logging support for receiving, completing or failing commands.
- [Doctrine](https://github.com/thephpleague/tactician-doctrine): Wraps commands in separate Doctrine ORM transactions.
- [Bernard](https://github.com/thephpleague/tactician-bernard): Allows queuing your commands in the background, using [the Bernard Queuing library](https://github.com/bernardphp/bernard).
- [Command Events](https://github.com/thephpleague/tactician-command-events): Fires events for all major moments in the command life-cycle.
- [Locking](http://tactician.thephpleague.com/plugins/locking-middleware/): Only allows one command to be executed at a time.

## Framework Integration
There is ongoing development for Zend, Symfony2 and Laravel integration packages. Check the github issues to see their status.
