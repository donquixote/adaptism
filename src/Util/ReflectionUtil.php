<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;

/**
 * @see \Roave\BetterReflection\BetterReflection
 */
class ReflectionUtil {

  /**
   * @param \ReflectionParameter[] $params
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return mixed[]|null
   */
  public static function paramsGetValues(array $params, ParamToValueInterface $paramToValue) {

    $else = new \stdClass();

    $argValues = [];
    foreach ($params as $i => $param) {
      if ($else === $argValue = $paramToValue->paramGetValue($param, $else)) {
        return NULL;
      }
      $argValues[$i] = $argValue;
    }

    return $argValues;
  }

}
