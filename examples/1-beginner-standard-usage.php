<?php
require __DIR__ . '/../vendor/autoload.php';

use Tactician\CommandBus\Handler\MethodNameInflector\HandleClassNameInflector;
use Tactician\CommandBus\Handler\Locator\InMemoryLocator;

// Our example Command and Handler. ///////////////////////////////////////////
class RegisterUserCommand
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

$commandBus = new Tactician\CommandBus\HandlerExecutionCommandBus(
    $locator,
    new HandleClassNameInflector()
);

// Controller Code ////////////////////////////////////////////////////////////
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->execute($command);
