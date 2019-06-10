<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\CanNotDetermineCommandName;
use PHPUnit\Framework\TestCase;
use stdClass;

class CanNotDetermineCommandNameExceptionTest extends TestCase
{
    public function testCanRetrieveFailingCommandFromException() : void
    {
        $exception = CanNotDetermineCommandName::forCommand($command = new stdClass());
        self::assertSame($command, $exception->getCommand());
    }
}
