<?php

namespace League\Tactician\Tests\Fixtures\Handler\Locator;

use Psr\Container\NotFoundExceptionInterface;

class SimpleNotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
}
