<?php
require __DIR__ . '/../vendor/autoload.php';

// The basic code from example 1 that we reuse in future examples.

use Tactician\CommandBus\Handler\MethodNameInflector\HandleClassNameInflector;
use Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use Tactician\CommandBus\Command;

class RegisterUserCommand implements Command
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUserCommand(RegisterUserCommand $command)
    {
        // Do your core application logic here. Don't actually echo things. :)
        echo "User {$command->emailAddress} was registered!\n";
    }
}

$locator = new InMemoryLocator();
$locator->addHandler(new RegisterUserHandler(), RegisterUserCommand::class);

$commandBus = new Tactician\CommandBus\HandlerExecutionCommandBus(
    $locator,
    new HandleClassNameInflector()
);
