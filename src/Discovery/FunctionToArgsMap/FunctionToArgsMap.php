<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FunctionToArgsMap;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_FreeArgs;
use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_MoreArgs;
use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_Simple;
use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_SimpleWithATA;
use Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface;
use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;

class FunctionToArgsMap implements FunctionToArgsMapInterface {

  /**
   * @var \Donquixote\Adaptism\ParamToValue\ParamToValueInterface|null
   */
  private $paramToValue;

  /**
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface|null $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue = NULL) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param \ReflectionFunctionAbstract $function
   *
   * @return \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function functionGetArgsMap(\ReflectionFunctionAbstract $function): ArgsMapInterface {

    $parameters = $function->getParameters();

    if (!isset($parameters[0])) {
      throw new Exception_ATABuilder("Missing first parameter.");
    }

    unset($parameters[0]);

    if ([] === $parameters) {
      return new ArgsMap_Simple();
    }

    if (ATAInterface::class !== $class = $parameters[1]->getClass()->getName()) {
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
