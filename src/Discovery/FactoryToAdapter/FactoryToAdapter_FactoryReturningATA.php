<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;
use Donquixote\Adaptism\Util\ReflectionUtil;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

class FactoryToAdapter_FactoryReturningATA implements FactoryToAdapterInterface {

  /**
   * @var \Donquixote\Adaptism\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory) {

    if (null === $returnTypeClass = $factory->getReturnTypeClass()) {
      return null;
    }

    $returnTypeClassName = $returnTypeClass->getName();

    if (!is_a($returnTypeClassName, ATAPartialInterface::class, true)) {
      return null;
    }

    $args = ReflectionUtil::paramsGetValues(
      $factory->getParameters(),
      $this->paramToValue);

    if (null === $args) {
      return null;
    }

    $instance = $factory->invokeArgs($args);

    if (!$instance instanceof ATAPartialInterface) {
      return null;
    }

    return $instance;
  }
}
