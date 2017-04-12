<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Handler\Locator\ContainerLocator;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\Locator\SimpleContainer;
use League\Tactician\Tests\Fixtures\Handler\SimpleCommandHandler;

class ContainerLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandlerForCommand()
    {
        $handlerFactories = [
            CompleteTaskCommand::class => function () {
                return new SimpleCommandHandler();
            },
        ];
        $container = new SimpleContainer($handlerFactories);
        $handlerLocator = new ContainerLocator($container);

        $this->assertInstanceOf(
            SimpleCommandHandler::class,
            $handlerLocator->getHandlerForCommand(CompleteTaskCommand::class)
        );
    }

    /**
     * @expectedException \League\Tactician\Exception\MissingHandlerException
     */
    public function testGetHandlerForCommandThrowsOnUndefinedHandler()
    {
        (new ContainerLocator(new SimpleContainer([])))->getHandlerForCommand('undefined');
    }
}
