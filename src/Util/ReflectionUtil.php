<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

/**
 * @see \Roave\BetterReflection\BetterReflection
 */
class ReflectionUtil {

  /**
   * @param \ReflectionParameter[] $params
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return mixed[]|null
   */
  public static function paramsGetValues(array $params, ParamToValueInterface $paramToValue): ?array {

    // Create a unique value to compare against.
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
