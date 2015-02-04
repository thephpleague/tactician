<?php
require __DIR__ . '/../vendor/autoload.php';

use League\Tactician\Command;
use League\Tactician\Middleware;
use League\Tactician\CommandBus;

/**
 * If you're working with a very constrained domain where there's not so many
 * dependencies, you could skip handlers altogether and implement a more
 * classic version of the command pattern where commands execute themselves.
 *
 * Here's a Tactician version of the wikipedia Light Switch example.
 */
interface SelfExecutingCommand extends Command
{
    public function execute(Light $light);
}

class Light
{
    public $switchedOn = false;

    public function __toString()
    {
        $status = $this->switchedOn ? 'on' : 'off';
        return "Light is {$status}\n";
    }
}

class SwitchOff implements SelfExecutingCommand
{
    public function execute(Light $light)
    {
        $light->switchedOn = false;
    }
}

class SwitchOn implements SelfExecutingCommand
{
    public function execute(Light $light)
    {
        $light->switchedOn = true;
    }
}

class SelfExecutionMiddleware implements Middleware
{
    protected $light;

    public function __construct($light)
    {
        $this->light = $light;
    }

    public function execute(Command $command, callable $next)
    {
        if (!$command instanceof SelfExecutingCommand) {
            throw new InvalidArgumentException("Can not execute command");
        }

        return $command->execute($this->light);
    }
}

// Why doesn't the SelfExecutionMiddleware call $next anywhere? Well, it could
// but it's convention to not call $next any further once the command has been
// executed, which stops the chain from going further. Otherwise, you might
//have the same command get handled twice.

$light = new Light();
$commandBus = new CommandBus(
    [
        new SelfExecutionMiddleware($light)
    ]
);
echo $light;

$commandBus->execute(new SwitchOn());
echo $light;

$commandBus->execute(new SwitchOff());
echo $light;

/**
 * So, this highly contrived example might smack of the famous SimplePHPEasyPlus
 * but it can still be useful in larger apps or libraries.
 *
 * You might also notice that we've touched very little of Tactician: only the
 * CommandBus and two interfaces. Yet, we were able to completely
 * change the way it dispatched commands.
 *
 * We could even go a step further and only implement the CommandBus interface
 * with this SelfExecution logic. But implementing this as a middleware is
 * still really helpful: most plugins for Tactician are written as middleware
 * and this way, they can still be dropped in and used without any changes.
 */
