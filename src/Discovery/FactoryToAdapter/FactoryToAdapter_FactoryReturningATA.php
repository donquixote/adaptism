<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
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
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?ATAPartialInterface {

    if (null === $returnTypeClass = $factory->getReturnTypeClass()) {
      return null;
    }

    $returnTypeClassName = $returnTypeClass->getName();

    if (!is_a($returnTypeClassName, ATAPartialInterface::class, true)) {
      return null;
    }

    $instance = FactoryUtil::factoryInvokePTV(
      $factory,
      $this->paramToValue);

    if (!$instance instanceof ATAPartialInterface) {
      return null;
    }

    return $instance;
  }
}
