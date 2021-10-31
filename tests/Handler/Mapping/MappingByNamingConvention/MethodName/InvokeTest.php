<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\MethodName;

use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\Invoke;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

/**
 * Best. Test. Ever.
 */
class InvokeTest extends TestCase
{
    public function testReturnsInvokeMagicMethod() : void
    {
        $inflector = new Invoke();

        self::assertEquals(
            '__invoke',
            $inflector->getMethodName(CompleteTaskCommand::class)
        );
    }
}
