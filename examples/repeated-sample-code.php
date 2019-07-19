<?php
require __DIR__ . '/../vendor/autoload.php';

// The basic code from example 1 that we reuse in future examples.

use League\Container\Container;
use League\Tactician\Handler\ClassName\Suffix;
use League\Tactician\Handler\MethodName\HandleLastPartOfClassName;

class RegisterUser
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUser(RegisterUser $command)
    {
        // Do your core application logic here. Don't actually echo things. :)
        echo "User {$command->emailAddress} was registered!\n";
    }
}

$container = new Container();
$container->add(RegisterUserHandler::class);

$handlerMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
    $container,
    new Suffix('Handler'),
    new HandleLastPartOfClassName()
);

$commandBus = new \League\Tactician\CommandBus($handlerMiddleware);
