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
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Command;

class ContainerBasedHandlerLocator implements HandlerLocator
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getHandlerForCommand(Command $command)
    {
        // This is a cheesy naming strategy but it's just an example
        $handlerId = 'app.handler.' . get_class($command);
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
use League\Tactician\HandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;

$commandBus = new HandlerMiddleware(
    new ContainerBasedHandlerLocator($fakeContainer),
    new HandleClassNameInflector()
);

// Controller Code: even though we've changed out the whole loading system we
// will still see that user was registered.
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);
