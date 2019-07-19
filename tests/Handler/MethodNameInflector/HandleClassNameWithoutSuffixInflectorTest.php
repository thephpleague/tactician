<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use DateTime;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameWithoutSuffixInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

class HandleClassNameWithoutSuffixInflectorTest extends TestCase
{
    /** @var HandleClassNameWithoutSuffixInflector */
    private $inflector;

    protected function setUp() : void
    {
        $this->inflector   = new HandleClassNameWithoutSuffixInflector();
    }

    public function testRemovesCommandSuffixFromClasses() : void
    {
        self::assertEquals(
            'handleCompleteTask',
            $this->inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }

    public function testDoesNotChangeClassesWithoutSuffix() : void
    {
        self::assertEquals(
            'handleDateTime',
            $this->inflector->inflect(DateTime::class, ConcreteMethodsHandler::class)
        );
    }

    public function testRemovesCustomSuffix() : void
    {
        $inflector = new HandleClassNameWithoutSuffixInflector('Time');

        self::assertEquals(
            'handleDate',
            $inflector->inflect(DateTime::class, ConcreteMethodsHandler::class)
        );
    }
}
