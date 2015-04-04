<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

/**
 * Let's also say you don't want to instantiate every handler at startup but
 * you'd rather pull them out of some Symfony/Zend/Laravel DI container, maybe
 * with a cool tags setup or some sort of id inflection.
 *
 * We can create a custom HandlerLocator for that.
 */
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;

class ContainerBasedHandlerLocator implements HandlerLocator
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    // The commandName is determined by the "CommandNameExtractor" we pass into
    // the CommandHandlerMiddleware below. If you look below, you can see it's
    // just the Class Name.
    public function getHandlerForCommand($commandName)
    {
        // This is a cheesy naming strategy but it's just an example
        $handlerId = 'app.handler.' . $commandName;
        return $this->container->get($handlerId);
    }
}

// Just a fake container: use the Symfony/Zend/Laravel one in real life.
$fakeContainer = Mockery::mock();
$fakeContainer
    ->shouldReceive('get')
    ->with('app.handler.RegisterUserCommand')
    ->andReturn(new RegisterUserHandler());

// Now, we create our command bus using our container based loader instead
$handlerMiddleware = new CommandHandlerMiddleware(
    new ClassNameExtractor(),
    new ContainerBasedHandlerLocator($fakeContainer),
    new HandleClassNameInflector()
);
$commandBus = new CommandBus([$handlerMiddleware]);

// Controller Code: even though we've changed out the whole loading system we
// will still see that user was registered.
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);
