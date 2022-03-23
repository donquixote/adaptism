<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\ATA\Partial\ATAPartial_ClassInstance;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_StaticMethod;
use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Discovery\FactoryToArgsMap\FactoryToArgsMap;
use Donquixote\Adaptism\Discovery\FactoryToArgsMap\FactoryToArgsMapInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FactoryToAdapter implements FactoryToAdapterInterface {

  /**
   * @var FactoryToArgsMapInterface
   */
  private $factoryToArgsMap;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface
   */
  public static function create(ParamToValueInterface $paramToValue): FactoryToAdapterInterface {

    $fta = self::fromPTV($paramToValue);

    $fta = new FactoryToAdapter_Chain([
      new FactoryToAdapter_FactoryReturningATA($paramToValue),

      $fta,
    ]);

    $fta = new FactoryToAdapter_CheckAnnotation($fta);

    return $fta;
  }

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null $paramToValue
   *
   * @return self
   */
  public static function fromPTV(ParamToValueInterface $paramToValue = null): self {
    return new self(new FactoryToArgsMap($paramToValue));
  }

  /**
   * @param \Donquixote\Adaptism\Discovery\FactoryToArgsMap\FactoryToArgsMapInterface $factoryToArgsMap
   */
  public function __construct(FactoryToArgsMapInterface $factoryToArgsMap) {
    $this->factoryToArgsMap = $factoryToArgsMap;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?ATAPartialInterface {

    $parameters = $factory->getParameters();

    $returnType = $factory->getReturnType();

    if ($returnType->isBuiltin()) {
      $typeName = $returnType->getName();

      if ('object' === $typeName) {
        // Factory can various types of objects.
        $returnTypeClass = null;
      }
      else {
        // We are only interested in factories that return objects.
        return null;
      }
    }
    elseif (null === $returnTypeClass = $factory->getReturnTypeClass()) {
      // This is a weird case. Get out of here.
      return null;
    }

    if (null === $parameter = $parameters[0] ?? null) {
      return null;
    }

    if (null === $sourceTypeClass = $parameter->getClass()) {
      return null;
    }

    $reflector = $factory->getReflector();

    if ($reflector instanceof \ReflectionClass) {

      if (!$reflector->isInstantiable()) {
        return null;
      }

      $partial = new ATAPartial_ClassInstance(
        $reflector,
        $this->factoryToArgsMap->factoryGetArgsMap($factory),
        $sourceTypeClass->getName());
    }
    elseif ($reflector instanceof \ReflectionMethod) {

      if ($reflector->isAbstract()) {
        return null;
      }

      if (!$reflector->isStatic()) {
        return null;
      }

      $partial = new ATAPartial_StaticMethod(
        $reflector,
        $this->factoryToArgsMap->factoryGetArgsMap($factory),
        $sourceTypeClass->getName(),
        $returnTypeClass->getName());
    }
    else {
      return null;
    }

    return $partial;
  }

}
