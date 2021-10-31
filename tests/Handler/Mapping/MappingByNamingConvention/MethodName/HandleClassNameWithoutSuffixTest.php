<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\MethodName;

use DateTime;
use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\HandleClassNameWithoutSuffix;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

class HandleClassNameWithoutSuffixTest extends TestCase
{
    /** @var HandleClassNameWithoutSuffix */
    private $inflector;

    protected function setUp() : void
    {
        $this->inflector   = new HandleClassNameWithoutSuffix();
    }

    public function testRemovesCommandSuffixFromClasses() : void
    {
        self::assertEquals(
            'handleCompleteTask',
            $this->inflector->getMethodName(CompleteTaskCommand::class)
        );
    }

    public function testDoesNotChangeClassesWithoutSuffix() : void
    {
        self::assertEquals(
            'handleDateTime',
            $this->inflector->getMethodName(DateTime::class)
        );
    }

    public function testRemovesCustomSuffix() : void
    {
        $inflector = new HandleClassNameWithoutSuffix('Time');

        self::assertEquals(
            'handleDate',
            $inflector->getMethodName(DateTime::class)
        );
    }
}
