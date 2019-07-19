<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use CommandWithoutNamespace;
use League\Tactician\Handler\MethodName\HandleLastPartOfClassName;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

class HandleClassNameTest extends TestCase
{
    /** @var HandleLastPartOfClassName */
    private $inflector;

    protected function setUp() : void
    {
        $this->inflector   = new HandleLastPartOfClassName();
    }

    public function testHandlesClassesWithoutNamespace() : void
    {
        $command = new CommandWithoutNamespace();

        self::assertEquals(
            'handleCommandWithoutNamespace',
            $this->inflector->inflect(CommandWithoutNamespace::class, ConcreteMethodsHandler::class)
        );
    }

    public function testHandlesNamespacedClasses() : void
    {
        $command = new CompleteTaskCommand();

        self::assertEquals(
            'handleCompleteTaskCommand',
            $this->inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
