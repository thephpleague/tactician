<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MethodName;

use CommandWithoutNamespace;
use League\Tactician\Handler\Mapping\MethodName\LastPartOfClassName;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

class LastPartOfClassNameTest extends TestCase
{
    /** @var LastPartOfClassName */
    private $inflector;

    protected function setUp(): void
    {
        $this->inflector = new LastPartOfClassName();
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
