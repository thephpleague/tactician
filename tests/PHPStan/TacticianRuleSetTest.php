<?php
declare(strict_types=1);

namespace League\Tactician\Tests\PHPStan;

use League\Tactician\Handler\ClassName\Suffix;
use League\Tactician\Handler\MethodName\Handle;
use League\Tactician\PHPStan\TacticianRuleSet;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

final class TacticianRuleSetTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $rules = new TacticianRuleSet(
            new Suffix('Handler'),
            new Handle()
        );
        $rules->setBroker($this->createBroker());

        return $rules;
    }

    public function getDynamicMethodReturnTypeExtensions(): array
    {
        return [new TacticianRuleSet(new Suffix('Handler'), new Handle())];
    }

    public function testCanNotFindMatchingHandlerClass(): void
    {
        $this->analyse(
            [__DIR__ . '/data/MissingHandlerClass.php'],
            [
                [
                    'Tactician tried to route the command MissingHandlerClass\SomeCommand ' .
                    'but could not find the matching handler MissingHandlerClass\SomeCommandHandler.',
                    14
                ]
            ]
        );
    }

    public function testCanNotFindCorrectMethodOnHandler(): void
    {
        $this->analyse(
            [__DIR__ . '/data/MissingHandlerMethod.php'],
            [
                [
                    'Tactician tried to route the command MissingHandlerMethod\SomeCommand to ' .
                    'MissingHandlerMethod\SomeCommandHandler::handle but while the class could be loaded, ' .
                    'the method \'handle\' could not be found on the class.',
                    21
                ]
            ]
        );
    }

    public function testHandlerDoesNotTakeParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/data/HandlerDoesNotTakeParameters.php'],
            [
                [
                    'Tactician tried to route the command HandlerDoesNotTakeParameters\SomeCommand to ' .
                    'HandlerDoesNotTakeParameters\SomeCommandHandler::handle but the method \'handle\' ' .
                    'does not accept any parameters.',
                    21
                ]
            ]
        );
    }

    public function testHandlerTakesTooManyParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/data/HandlerTakesTooManyParameters.php'],
            [
                [
                    'Tactician tried to route the command HandlerTakesTooManyParameters\SomeCommand to ' .
                    'HandlerTakesTooManyParameters\SomeCommandHandler::handle but the method \'handle\' ' .
                    'accepts too many parameters.',
                    21
                ]
            ]
        );
    }

    public function testAcceptsExactParameterTypehintMatch(): void
    {
        $this->analyse(
            [__DIR__ . '/data/AcceptsExactParameterTypehintMatch.php'],
            [
            ]
        );
    }

    public function testAcceptsSuperclassAsParameterTypeHint(): void
    {
        $this->analyse(
            [__DIR__ . '/data/AcceptsSuperclassAsParameterTypeHint.php'],
            [
            ]
        );
    }

    public function testAcceptsGenericObjectTypehint(): void
    {
        $this->analyse(
            [__DIR__ . '/data/AcceptsGenericObjectTypehint.php'],
            [
            ]
        );
    }

    public function testDoesNotAcceptSubclassAsParameterTypeHint(): void
    {
        $this->analyse(
            [__DIR__ . '/data/DoesNotAcceptSubclassAsParameterTypeHint.php'],
            [
                [
                    'Tactician tried to route the command DoesNotAcceptSubclassAsParameterTypeHint\SomeCommand to ' .
                    'DoesNotAcceptSubclassAsParameterTypeHint\SomeCommandHandler::handle but the method \'handle\' ' .
                    'has a typehint that does not allow this command.',
                    26
                ]
            ]
        );
    }

    public function testDoesNotAcceptSomeUnrelatedScalarParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/data/DoesNotAcceptSomeUnrelatedScalarParameter.php'],
            [
                [
                    'Tactician tried to route the command DoesNotAcceptSomeUnrelatedScalarParameter\SomeCommand to ' .
                    'DoesNotAcceptSomeUnrelatedScalarParameter\SomeCommandHandler::handle but the method \'handle\' ' .
                    'has a typehint that does not allow this command.',
                    21
                ]
            ]
        );
    }

    public function testCommandBusCalledWithTooManyParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/data/CommandBusCalledWithTooManyParameters.php'],
            [
            ]
        );
    }

    public function testDoesNotInterfereWithOtherMethodCalls(): void
    {
        $this->analyse(
            [__DIR__ . '/data/DoesNotInterfereWithOtherMethodCalls.php'],
            [
            ]
        );
    }

    public function testAcceptsMissingParameterTypehint(): void
    {
        $this->analyse(
            [__DIR__ . '/data/AcceptsMissingParameterTypehint.php'],
            [
            ]
        );
    }
}
