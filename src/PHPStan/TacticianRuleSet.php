<?php
declare(strict_types=1);

namespace League\Tactician\PHPStan;

use League\Tactician\CommandBus;
use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use function array_filter;
use function array_merge;

final class TacticianRuleSet implements Rule
{
    /**
     * @var CommandToHandlerMapping
     */
    private $mapping;
    /**
     * @var Broker
     */
    private $broker;

    public function __construct(CommandToHandlerMapping $mapping, Broker $broker)
    {
        $this->mapping = $mapping;
        $this->broker = $broker;
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $methodCall, Scope $scope): array
    {
        if (! $methodCall instanceof MethodCall) {
            return [];
        }

        $type = $scope->getType($methodCall->var);

        if (! (new ObjectType(CommandBus::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        // Wrong number of arguments passed to handle? Delegate to other PHPStan rules
        if (count($methodCall->args) !== 1) {
            return []; //
        }

        $commandType = $scope->getType($methodCall->args[0]->value);

        $errors = [];
        foreach ($this->getInspectableCommandTypes($commandType) as $commandType) {
            $errors = array_merge(
                $errors,
                $this->inspectCommandType($methodCall, $scope, $commandType)
            );
        }

        return $errors;
    }

    /**
     * @return array<string>
     */
    private function inspectCommandType(
        MethodCall $methodCallOnBus,
        Scope $scope,
        TypeWithClassName $commandType
    ): array {
        $handlerClassName = $this->mapping->getClassName($commandType->getClassName());

        try {
            $handlerClass = $this->broker->getClass($handlerClassName);
        } catch (ClassNotFoundException $e) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} but could not find the matching handler {$handlerClassName}.",
            ];
        }

        $handlerMethodName = $this->mapping->getMethodName($commandType->getClassName());

        try {
            $handlerMethod = $handlerClass->getMethod($handlerMethodName, $scope);
        } catch (MissingMethodFromReflectionException $e) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$handlerMethodName} but while the class could be loaded, the method '{$handlerMethodName}' could not be found on the class.",
            ];
        }

        /** @var \PHPStan\Reflection\ParameterReflection[] $parameters */
        $parameters = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCallOnBus->args,
            $handlerMethod->getVariants()
        )->getParameters();

        if (count($parameters) === 0) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$handlerMethodName} but the method '{$handlerMethodName}' does not accept any parameters.",
            ];
        }

        if (count($parameters) > 1) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$handlerMethodName} but the method '{$handlerMethodName}' accepts too many parameters.",
            ];
        }

        if ($parameters[0]->getType()->accepts($commandType, true)->no()) {
            return [
                "Tactician tried to route the command {$commandType->getClassName()} to {$handlerClass->getName()}::{$handlerMethodName} but the method '{$handlerMethodName}' has a typehint that does not allow this command.",
            ];
        }

        return [];
    }

    /** @return TypeWithClassName[] */
    private function getInspectableCommandTypes(Type $type): array
    {
        if ($type instanceof TypeWithClassName) {
            return [$type];
        }

        if ($type instanceof UnionType) {
            return array_filter(
                $type->getTypes(),
                function (Type $type) {
                    return $type instanceof TypeWithClassName;
                }
            );
        }

        return [];
    }
}
