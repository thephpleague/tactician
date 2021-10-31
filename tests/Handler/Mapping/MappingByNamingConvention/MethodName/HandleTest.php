<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\MethodName;

use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\Handle;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

/**
 * Yet. Another. Best. Test. Ever.
 */
class HandleTest extends TestCase
{
    public function testReturnsHandleMethod() : void
    {
        $inflector = new Handle();

        self::assertEquals(
            'handle',
            $inflector->getMethodName(CompleteTaskCommand::class)
        );
    }
}
