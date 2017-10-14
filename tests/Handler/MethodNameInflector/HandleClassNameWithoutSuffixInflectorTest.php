<?php

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\HandleClassNameWithoutSuffixInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use DateTime;
use PHPUnit\Framework\TestCase;

class HandleClassNameWithoutSuffixInflectorTest extends TestCase
{
    /**
     * @var HandleClassNameWithoutSuffixInflector
     */
    private $inflector;

    /**
     * @var object
     */
    private $mockHandler;

    protected function setUp()
    {
        $this->inflector = new HandleClassNameWithoutSuffixInflector();
        $this->handler = new ConcreteMethodsHandler();
    }

    public function testRemovesCommandSuffixFromClasses()
    {
        $command = new CompleteTaskCommand();

        $this->assertEquals(
            'handleCompleteTask',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }

    public function testDoesNotChangeClassesWithoutSuffix()
    {
        $this->assertEquals(
            'handleDateTime',
            $this->inflector->inflect(new DateTime(), $this->mockHandler)
        );
    }

    public function testRemovesCustomSuffix()
    {
        $inflector = new HandleClassNameWithoutSuffixInflector('Time');

        $this->assertEquals(
            'handleDate',
            $inflector->inflect(new DateTime(), $this->mockHandler)
        );
    }
}
