<?php
declare(strict_types=1);

namespace League\Tactician\PHPStan;

use League\Tactician\CommandBus;
use League\Tactician\Handler\ClassName\ClassNameInflector;
use League\Tactician\Handler\MethodName\MethodNameInflector;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;

final class TacticianRuleSet implements Rule
{
    /**
     * @var ClassNameInflector
     */
    private $classNameInflector;

    /**
     * @var MethodNameInflector
     */
    private $methodNameInflector;

    /**
     * @var Broker
     */
    private $broker;

    public function __construct(
        ClassNameInflector $handlerNameInflector,
        MethodNameInflector $methodNameInflector,
        Broker $broker
    ) {
        $this->classNameInflector = $handlerNameInflector;
        $this->methodNameInflector = $methodNameInflector;
        $this->broker = $broker;
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $methodCall, Scope $scope): array
    {
        if (!$methodCall instanceof MethodCall) {
            return [];
        }

        $type = $scope->getType($methodCall->var);

        if (!(new ObjectType(CommandBus::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        // Wrong number of arguments passed to handle? Delegate to other PHPStan rules
        if (count($methodCall->args) !== 1) {
            return []; //
        }

        $commandType = $scope->getType($methodCall->args[0]->value);

        // did user violate the object typehint by passing something else?
        // exit to delegate to other PHPStan rules
        if (!$commandType instanceof TypeWithClassName) {
            return [];
        }

        $handlerClassName = $this->classNameInflector->getHandlerClassName($commandType->getClassName());

        try {
            $handlerClass = $this->broker->getClass($handlerClassName);
        } catch (ClassNotFoundException $e) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} but could not find the matching handler {$handlerClassName}."
            ];
        }

        $methodName = $this->methodNameInflector->inflect($commandType->getClassName(), $handlerClass->getName());

        if (!$handlerClass->hasMethod($methodName)) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$methodName} but while the class could be loaded, the method '{$methodName}' could not be found on the class."
            ];
        }

        /** @var \PHPStan\Reflection\ParameterReflection[] $parameters */
        $parameters = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCall->args,
            $handlerClass->getMethod($methodName, $scope)->getVariants()
        )->getParameters();

        if (count($parameters) === 0) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$methodName} but the method '{$methodName}' does not accept any parameters."
            ];
        }

        if (count($parameters) > 1) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$methodName} but the method '{$methodName}' accepts too many parameters."
            ];
        }

        if ($parameters[0]->getType()->accepts($commandType, true)->no()) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$methodName} but the method '{$methodName}' has a typehint that does not allow this command."
            ];
        }

        return [];
    }
}
