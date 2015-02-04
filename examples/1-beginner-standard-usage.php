<?php
require __DIR__ . '/../vendor/autoload.php';

use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Command;

// Our example Command and Handler. ///////////////////////////////////////////
class RegisterUserCommand implements Command
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUserCommand(RegisterUserCommand $command)
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
$handlerMiddleware = new League\Tactician\Handler\HandlerMiddleware(
    $locator,
    new HandleClassNameInflector()
);

$commandBus = new \League\Tactician\StandardCommandBus([$handlerMiddleware]);

// Controller Code ////////////////////////////////////////////////////////////
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);
