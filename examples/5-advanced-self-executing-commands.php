<?php
require __DIR__ . '/../vendor/autoload.php';

use Tactician\CommandBus\Command;

/**
 * If you're working with a very constrained domain where there's not so many
 * dependencies, you could skip handlers altogether and implement a more
 * classic version of the command pattern where commands execute themselves.
 *
 * Here's a Tactician version of the wikipedia Light Switch example.
 */
interface SelfExecutingCommand extends Command {
    public function execute(Light $light);
}

class Light
{
    public $switchedOn = false;

    public function __toString() {
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

use Tactician\CommandBus\CommandBus;
class SelfExecutingCommandBus implements CommandBus
{
    protected $light;

    public function __construct($light)
    {
        $this->light = $light;
    }

    public function execute(Command $command)
    {
        if (!$command instanceof SelfExecutingCommand) {
            throw new InvalidArgumentException("Can not execute command");
        }

        return $command->execute($this->light);
    }
}

$light = new Light();
$commandBus = new SelfExecutingCommandBus($light);
echo $light;

$commandBus->execute(new SwitchOn());
echo $light;

$commandBus->execute(new SwitchOff());
echo $light;

/**
 * So, this highly contrived example might smack of the famous SimplePHPEasyPlus
 * but it can still be useful in larger apps or libraries.
 *
 * You might also notice that we've used nothing of Tactician here except an
 * interface: without Handlers, we don't need a MethodNameInflector or a
 * HandlerLocator at all.
 *
 * But using only the interface is still really helpful: decorators and plugins
 * designed to work with Tactician can still be dropped onto our custom command
 * without any changes at all.
 */
