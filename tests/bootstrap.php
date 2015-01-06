<?php
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('Tactician\\CommandBus\\Tests\\', __DIR__);

// In lieu of autoloading hacks, we'll just do it this way.
require_once __DIR__.'/Fixtures/Command/CommandWithoutNamespace.php';
