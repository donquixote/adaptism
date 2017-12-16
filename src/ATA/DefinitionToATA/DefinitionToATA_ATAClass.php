<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\DefinitionToATA;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;

class DefinitionToATA_ATAClass implements DefinitionToATAInterface {

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
   * @param array $definition
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function definitionGetPartial(array $definition): ATAPartialInterface {

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

    if ($instance instanceof ATAPartialInterface) {
      return $instance;
    }

    $class = $reflClass->getName();
    $interfaceExpected = ATAPartialInterface::class;
    throw new Exception_ATABuilder("Class $class does not implement $interfaceExpected.");
  }


}
