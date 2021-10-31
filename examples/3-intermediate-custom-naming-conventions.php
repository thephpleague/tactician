<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repeated-sample-code.php';

/**
 * Tactician is meant to be very customizable, it makes no assumptions about
 * your code.
 *
 * For example, let's say you don't like that your handler methods need to be
 * called "handleNameOfYourCommand" and you'd rather it always use a method
 * named "handle".
 *
 * We can write a custom MethodNameInflector for that:
 */
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Mapping\MapByNamingConvention\MapByNamingConvention;
use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\MethodNameInflector;
use League\Tactician\Handler\Mapping\MapByNamingConvention\ClassName\Suffix;

class MyCustomMethodNameInflector implements MethodNameInflector
{
    // You can use the command to generate any name you
    // prefer but here, we'll always return the same one.
    public function getMethodName(string $commandClassName): string
    {
        return 'handle';
    }
}

class DeleteUser
{
}

// And we'll create a new handler with the method name we prefer to invoke
class DeleteUserHandler
{
    public function handle($command)
    {
        echo "See, Tactician now calls the handle method we prefer!\n";
    }
}

// Now  let's recreate our CommandHandlerMiddleware again but with the naming scheme
// we prefer to use!
$container->add(DeleteUserHandler::class);
$handlerMiddleware = new CommandHandlerMiddleware(
    $container,
    new MapByNamingConvention(
        new Suffix('Handler'),
        new MyCustomMethodNameInflector()
    )
);

$commandBus = new CommandBus($handlerMiddleware);

// Controller Code time!
$commandBus->handle(new DeleteUser());
