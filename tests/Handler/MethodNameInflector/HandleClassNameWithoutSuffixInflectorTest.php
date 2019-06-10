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

    /** @var object */
    private $mockHandler;

    protected function setUp() : void
    {
        $this->inflector   = new HandleClassNameWithoutSuffixInflector();
        $this->mockHandler = new ConcreteMethodsHandler();
    }

    public function testRemovesCommandSuffixFromClasses() : void
    {
        $command = new CompleteTaskCommand();

        self::assertEquals(
            'handleCompleteTask',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }

    public function testDoesNotChangeClassesWithoutSuffix() : void
    {
        self::assertEquals(
            'handleDateTime',
            $this->inflector->inflect(new DateTime(), $this->mockHandler)
        );
    }

    public function testRemovesCustomSuffix() : void
    {
        $inflector = new HandleClassNameWithoutSuffixInflector('Time');

        self::assertEquals(
            'handleDate',
            $inflector->inflect(new DateTime(), $this->mockHandler)
        );
    }
}
