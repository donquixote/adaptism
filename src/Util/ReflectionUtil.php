<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

class ReflectionUtil {

  /**
   * @param \ReflectionClassConstant|\ReflectionParameter|\ReflectionClass|\ReflectionProperty|\ReflectionFunctionAbstract $reflector
   *
   * @return string
   */
  public static function reflectorDebugName(
    \ReflectionClassConstant|\ReflectionParameter|\ReflectionClass|\ReflectionProperty|\ReflectionFunctionAbstract $reflector,
  ): string {
    $name = $reflector->getName();
    if ($reflector instanceof \ReflectionParameter) {
      return 'parameter $' . $name . ' of '
        . self::reflectorDebugName($reflector->getDeclaringFunction());
    }
    if ($reflector instanceof \ReflectionFunctionAbstract) {
      $name .= '()';
    }
    if ($reflector instanceof \ReflectionProperty) {
      $name = '$' . $name;
    }
    if ($reflector instanceof \ReflectionProperty
      || $reflector instanceof \ReflectionClassConstant
      || $reflector instanceof \ReflectionMethod
    ) {
      $name = $reflector->getDeclaringClass()->getName() . '::' . $name;
    }
    return $name;
  }

}
