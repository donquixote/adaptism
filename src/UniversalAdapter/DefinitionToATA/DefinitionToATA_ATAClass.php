<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\DefinitionToATA;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class DefinitionToATA_ATAClass implements DefinitionToATAInterface {

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
   * @param array $definition
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function definitionGetPartial(array $definition): SpecificAdapterInterface {

    $reflClass = DefinitionToATAUtil::definitionGetReflectionClass(
      $definition,
      true);

    if (null === $constructor = $reflClass->getConstructor()) {
      $class = $reflClass->getName();
      throw new Exception_ATABuilder("Class '$class' has no constructor.");
    }

    $parameters = $constructor->getParameters();

    $else = new \stdClass();

    $args = [];
    foreach ($parameters as $i => $parameter) {

      if ($else === $arg = $this->paramToValue->paramGetValue($parameter, $else)) {
        throw new Exception_ATABuilder("No value found for parameter $i.");
      }

      $args[] = $arg;
    }

    $instance = $reflClass->newInstanceArgs($args);

    if ($instance instanceof SpecificAdapterInterface) {
      return $instance;
    }

    $class = $reflClass->getName();
    $interfaceExpected = SpecificAdapterInterface::class;
    throw new Exception_ATABuilder("Class $class does not implement $interfaceExpected.");
  }


}
