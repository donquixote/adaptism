<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\DefinitionToATA;

use Donquixote\Adaptism\ATA\Partial\ATAPartial_ClassInstance;
use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Discovery\FunctionToArgsMap\FunctionToArgsMapInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;

class DefinitionToATA_AdapterClass implements DefinitionToATAInterface {

  /**
   * @var \Donquixote\Adaptism\Discovery\FunctionToArgsMap\FunctionToArgsMapInterface
   */
  private $functionToArgsMap;

  /**
   * @param \Donquixote\Adaptism\Discovery\FunctionToArgsMap\FunctionToArgsMapInterface $functionToArgsMap
   */
  public function __construct(FunctionToArgsMapInterface $functionToArgsMap) {
    $this->functionToArgsMap = $functionToArgsMap;
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

    if (null === $parameter = $parameters[0] ?? null) {
      throw new Exception_ATABuilder("Missing first parameter.");
    }

    if (null === $sourceTypeClass = $parameter->getClass()) {
      // @todo Make this a "universal" adapter?
      throw new Exception_ATABuilder("No class type hint found for first parameter.");
    }

    return new ATAPartial_ClassInstance(
      $reflClass,
      $this->functionToArgsMap->functionGetArgsMap($constructor),
      $sourceTypeClass->getName());
  }


}
