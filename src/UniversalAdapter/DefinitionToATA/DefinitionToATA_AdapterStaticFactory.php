<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\DefinitionToATA;

use Donquixote\Adaptism\SpecificAdapter\ATAPartial_StaticMethod;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Adaptism\Discovery\FunctionToArgsMap\FunctionToArgsMapInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;

class DefinitionToATA_AdapterStaticFactory implements DefinitionToATAInterface {

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
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function definitionGetPartial(array $definition): SpecificAdapterInterface {

    $reflMethod = DefinitionToATAUtil::definitionGetReflectionMethod(
      $definition,
      true,
      \ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC,
      \ReflectionMethod::IS_ABSTRACT);

    $parameters = $reflMethod->getParameters();

    if (null === $parameter = $parameters[0] ?? null) {
      throw new Exception_ATABuilder("Missing first parameter.");
    }

    if (null === $sourceTypeClass = $parameter->getClass()) {
      // @todo Make this a "universal" adapter?
      throw new Exception_ATABuilder("No class type hint found for first parameter.");
    }

    return new ATAPartial_StaticMethod(
      $reflMethod,
      $this->functionToArgsMap->functionGetArgsMap($reflMethod),
      $sourceTypeClass,
      null);
  }


}
