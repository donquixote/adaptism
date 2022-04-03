<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToArgsMap;

use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMap_FreeArgs;
use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMap_MoreArgs;
use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMap_Simple;
use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMap_SimpleWithATA;
use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FactoryToArgsMap implements FactoryToArgsMapInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null
   */
  private $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue = NULL) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface
   */
  public function factoryGetArgsMap(ReflectionFactoryInterface $factory): ArgsMapInterface {

    $parameters = $factory->getParameters();

    unset($parameters[0]);

    if ([] === $parameters) {
      return new ArgsMap_Simple();
    }

    if (UniversalAdapterInterface::class !== $class = $parameters[1]->getClass()->getName()) {
      $argsMap = new ArgsMap_Simple();
    }
    else {
      $argsMap = new ArgsMap_SimpleWithATA();
      unset($parameters[1]);
      if ([] === $parameters) {
        return $argsMap;
      }
    }

    $else = new \stdClass();

    $defaultArgs = [];
    $hasMoreArgs = false;
    $freeArgs = [];
    foreach ($parameters as $i => $parameter) {
      if (null !== $this->paramToValue
        && $else !== ($argValue = $this->paramToValue->paramGetValue($parameter, $else))
      ) {
        $defaultArgs[$i] = $argValue;
        $hasMoreArgs = true;
      }
      else {
        $defaultArgs[$i] = null;
        $freeArgs[$i] = $class;
      }
    }

    if ($hasMoreArgs) {
      $argsMap = new ArgsMap_MoreArgs($argsMap, $defaultArgs);
    }

    if ([] !== $freeArgs) {
      $argsMap = new ArgsMap_FreeArgs($argsMap, $freeArgs);
    }

    return $argsMap;
  }

}
