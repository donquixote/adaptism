<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Util\ReflectionUtil;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FactoryToAdapter_FactoryReturningCallable implements FactoryToAdapterInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?ATAPartialInterface {

    $returnType = $factory->getReturnType();

    if ('callable' !== $returnType->getName() || !$returnType->isBuiltin()) {
      return null;
    }

    $args = ReflectionUtil::paramsGetValues(
      $factory->getParameters(),
      $this->paramToValue);

    if (null === $args) {
      return null;
    }

    $callable = $factory->invokeArgs($args);

    if (!\is_callable($callable)) {
      return null;
    }



    if (!$callable instanceof ATAPartialInterface) {
      return null;
    }

    return $callable;
  }
}
