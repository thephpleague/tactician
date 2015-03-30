<?php
require __DIR__ . '/../vendor/autoload.php';

use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\Locator\InMemoryLocator;

// Our example Command and Handler. ///////////////////////////////////////////
class RegisterUserCommand
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUserphp (RegisterUserCommand $command)
    {
        // Do your core application logic here. Don't actually echo stuff. :)
        echo "User {$command->emailAddress} was registered!\n";
    }
}

// Setup the bus, normally in your DI container ///////////////////////////////
$locator = new InMemoryLocator();
$locator->addHandler(new RegisterUserHandler(), RegisterUserCommand::class);

// Middleware is Tactician's plugin system. Even finding the handler and
// executing it is a plugin that we're configuring here.
$handlerMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
    $locator,
    new HandleClassNameInflector()
);

$commandBus = new \League\Tactician\CommandBus([$handlerMiddleware]);

// Controller Code ////////////////////////////////////////////////////////////
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);
