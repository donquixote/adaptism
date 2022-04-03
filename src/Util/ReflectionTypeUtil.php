<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

class ReflectionTypeUtil {

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   *
   * @return string
   * @throws \ReflectionException
   */
  public static function requireGetClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
  ): string {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
      throw new \ReflectionException(\sprintf(
        'Expected a class-like type declaration on %s.',
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
    $name = $type->getName();
    if ($name !== 'self' && $name !== 'static') {
      return $name;
    }
    $reflectionFunction = $reflector instanceof \ReflectionParameter
      ? $reflector->getDeclaringFunction()
      : $reflector;
    if (!$reflectionFunction instanceof \ReflectionMethod) {
      throw new \ReflectionException(\sprintf(
        'Unexpected %s outside class context, in type declaration for %s.',
        "'$name'",
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
    return $reflectionFunction->getDeclaringClass()->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param class-string $expected
   *
   * @throws \ReflectionException
   */
  public static function requireClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    string $expected,
  ): void {
    $name = self::requireGetClassLikeType($reflector);
    if ($name !== $expected) {
      throw new \ReflectionException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param string $expected
   *
   * @throws \ReflectionException
   */
  public static function requireBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    string $expected,
  ): void {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin() || $type->getName() !== $expected) {
      throw new \ReflectionException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        ReflectionUtil::reflectorDebugName($reflector),
      ));
    }
  }

}
