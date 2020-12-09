<?php

namespace League\Tactician\Tests\Exception;

use League\Tactician\Exception\InvalidCommandException;
use League\Tactician\Exception\Exception;
use PHPUnit\Framework\TestCase;

class InvalidCommandExceptionTest extends TestCase
{
    public function testExceptionContainsDebuggingInfo()
    {
        $command = 'must be an object';

        $exception = InvalidCommandException::forUnknownValue($command);

        $this->assertStringContainsString('type: string', $exception->getMessage());
        $this->assertSame($command, $exception->getInvalidCommand());
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
