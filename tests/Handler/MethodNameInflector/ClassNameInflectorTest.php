<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use CommandWithoutNamespace;
use League\Tactician\Handler\MethodNameInflector\ClassNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

class ClassNameInflectorTest extends TestCase
{
    /** @var ClassNameInflector */
    private $inflector;

    protected function setUp(): void
    {
        $this->inflector = new ClassNameInflector();
    }

    public function testHandlesClassesWithoutNamespace(): void
    {
        self::assertEquals(
            'commandWithoutNamespace',
            $this->inflector->inflect(CommandWithoutNamespace::class, ConcreteMethodsHandler::class)
        );
    }

    public function testHandlesNamespacedClasses(): void
    {
        self::assertEquals(
            'completeTaskCommand',
            $this->inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
