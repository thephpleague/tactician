<?php
declare(strict_types=1);

namespace League\Tactician\PHPStan;

use League\Tactician\CommandBus;
use League\Tactician\Handler\ClassName\ClassNameInflector;
use League\Tactician\Handler\MethodName\MethodNameInflector;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class HandlerReturnTypeExtension implements DynamicMethodReturnTypeExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    private $broker;

    /**
     * @var ClassNameInflector
     */
    private $classNameInflector;

    /**
     * @var MethodNameInflector
     */
    private $methodNameInflector;

    public function __construct(
        ClassNameInflector $handlerNameInflector,
        MethodNameInflector $methodNameInflector
    ) {
        $this->classNameInflector = $handlerNameInflector;
        $this->methodNameInflector = $methodNameInflector;
    }

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function getClass(): string
    {
        return CommandBus::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'handle';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $commandType = $scope->getType($methodCall->args[0]->value);

        if (!$commandType instanceof ObjectType) {
            return new MixedType();
        }

        try {
            $handlerClass = $this->broker->getClass(
                $this->classNameInflector->getHandlerClassName($commandType->getClassName())
            );
        } catch (ClassNotFoundException $e) {
            return new MixedType();
        }

        $methodName = $this->methodNameInflector->inflect($commandType->getClassName(), $handlerClass->getName());

        try {
            $method = $handlerClass->getMethod($methodName, $scope)->getVariants();
        } catch (MissingMethodFromReflectionException $e) {
            return new MixedType();
        }

        return ParametersAcceptorSelector::selectFromArgs($scope, $methodCall->args, $method)->getReturnType();
    }
}
