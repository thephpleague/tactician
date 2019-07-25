<?php
require __DIR__ . '/../vendor/autoload.php';

use League\Container\Container;
use League\Tactician\Handler\Mapping\ClassName\Suffix;
use League\Tactician\Handler\Mapping\MapByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\HandleLastPartOfClassName;

// Our example Command and Handler. ///////////////////////////////////////////
class RegisterUser
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUser(RegisterUser $command)
    {
        // Do your core application logic here. Don't actually echo stuff. :)
        echo "User {$command->emailAddress} was registered!\n";
    }
}

// Add your handlers to your Dependency Injector container of choice. /////////
$container = new Container();
$container->add(RegisterUserHandler::class);

// Middleware is Tactician's plugin system. Even finding the handler and
// executing it is a plugin that we're configuring here.
$handlerMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
    $container,
    new MapByNamingConvention(
        new Suffix('Handler'), // We expect our Handlers have the same class name except with this suffix
        new HandleLastPartOfClassName() // We expect the method name to be handle + the last part of the command name
    )
);

$commandBus = new \League\Tactician\CommandBus($handlerMiddleware);

// Controller Code ////////////////////////////////////////////////////////////
$command = new RegisterUser();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);
