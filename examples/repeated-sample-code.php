<?php
require __DIR__ . '/../vendor/autoload.php';

// The basic code from example 1 that we reuse in future examples.

use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\HandlerNameInflector\SuffixInflector;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;

class RegisterUserCommand
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

$handlerMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
    new SuffixInflector(),
    $locator,
    new HandleClassNameInflector()
);

$commandBus = new \League\Tactician\CommandBus([$handlerMiddleware]);
