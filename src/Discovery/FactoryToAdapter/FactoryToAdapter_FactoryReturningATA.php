<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\Util\FactoryUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FactoryToAdapter_FactoryReturningATA implements FactoryToAdapterInterface {

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
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SpecificAdapterInterface {

    if (null === $returnTypeClass = $factory->getReturnTypeClass()) {
      return null;
    }

    $returnTypeClassName = $returnTypeClass->getName();

    if (!is_a($returnTypeClassName, SpecificAdapterInterface::class, true)) {
      return null;
    }

    $instance = FactoryUtil::factoryInvokePTV(
      $factory,
      $this->paramToValue);

    if (!$instance instanceof SpecificAdapterInterface) {
      return null;
    }

    return $instance;
  }
}
