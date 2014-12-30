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

use Tactician\CommandBus\Handler\MethodNameInflector\MethodNameInflector;

class MyCustomInflector implements MethodNameInflector
{
    // You can use the command and commandHandler to generate any name you
    // prefer but here, we'll always return the same one.
    public function inflect($command, $commandHandler)
    {
        return 'handle';
    }
}

// And we'll create a new handler with the method name we prefer to invoke
class NewRegisterUserHandler
{
    public function handle($command)
    {
        echo "See, Tactician now calls the handle method we prefer!\n";
    }
}

// Now  let's create our command bus again, but this time using our custom
// method naming strategy
use Tactician\CommandBus\ExecutesHandlerCommandBus;
$locator->addHandler(new NewRegisterUserHandler(), RegisterUserCommand::class);
$commandBus = new ExecutesHandlerCommandBus($locator, new MyCustomInflector());

// Controller Code time!
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);
