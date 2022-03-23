<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\DefinitionToATA;

use Donquixote\Adaptism\Discovery\FunctionToArgsMap\FunctionToArgsMap;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class DefinitionToATA {

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface
   */
  public static function create(ParamToValueInterface $paramToValue): DefinitionToATAInterface {

    $functionToArgsMap = new FunctionToArgsMap($paramToValue);

    return new DefinitionToATA_DispatchByType(
      [
        'adapterClass' => new DefinitionToATA_AdapterClass(
          $functionToArgsMap),
        'adapterStaticFactory' => new DefinitionToATA_AdapterStaticFactory(
          $functionToArgsMap),
        'ataClass' => new DefinitionToATA_ATAClass($paramToValue),
        'ataStaticFactory' => new DefinitionToATA_ATAStaticFactory($paramToValue),
      ]);
  }

}
