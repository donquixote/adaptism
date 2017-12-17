<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\DefinitionToATA;

use Donquixote\Adaptism\Exception\Exception_ATABuilder;

class DefinitionToATAUtil {

  /**
   * @param array $definition
   * @param bool $requireInstantiable
   *
   * @return \ReflectionClass
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public static function definitionGetReflectionClass(
    array $definition,
    bool $requireInstantiable = false
  ): \ReflectionClass {

    if (null === $class = $definition['class'] ?? null) {
      throw new Exception_ATABuilder("Missing key 'class' in definition.");
    }

    if (!\is_string($class)) {
      $classtype = \gettype($class);
      throw new Exception_ATABuilder("Expected string for key 'class' in definition, $classtype found instead.");
    }

    if (!class_exists($class)) {
      throw new Exception_ATABuilder("Class '$class' found in definition does not exist.");
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
    $reflClass = new \ReflectionClass($class);

    if ($requireInstantiable && !$reflClass->isInstantiable()) {
      $class = $reflClass->getName();
      throw new Exception_ATABuilder("Class '$class' is not instantiable.");
    }

    return $reflClass;
  }

  /**
   * @param array $definition
   * @param bool $requireOwnClass
   * @param int $requiredModifiers
   * @param int $forbiddenModifiers
   *
   * @return \ReflectionMethod
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public static function definitionGetReflectionMethod(
    array $definition,
    bool $requireOwnClass = false,
    int $requiredModifiers = 0,
    int $forbiddenModifiers = 0
  ): \ReflectionMethod {

    if (null === $class = $definition['class'] ?? null) {
      throw new Exception_ATABuilder("Missing key 'class' in definition.");
    }

    if (null === $methodName = $definition['method'] ?? null) {
      throw new Exception_ATABuilder("Missing key 'method' in definition.");
    }

    if (!\is_string($class)) {
      $classtype = \gettype($class);
      throw new Exception_ATABuilder("Expected string for key 'class' in definition, $classtype found instead.");
    }

    if (!\is_string($methodName)) {
      $methodtype = \gettype($methodName);
      throw new Exception_ATABuilder("Expected string for key 'class' in definition, $methodtype found instead.");
    }

    if (!class_exists($class)) {
      throw new Exception_ATABuilder("Class '$class' specified in definition does not exist.");
    }

    if (!method_exists($class, $methodName)) {
      throw new Exception_ATABuilder("Method '$class::$methodName()' specified in definition does not exist.");
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
    $reflMethod = new \ReflectionMethod($class, $methodName);

    $modifiers = $reflMethod->getModifiers();

    if (0 !== $missingModifiers = ~$modifiers & $requiredModifiers) {
      throw new Exception_ATABuilder("Method '$class::$methodName()' lacks required modifiers $missingModifiers.");
    }

    if (0 === $illegalModifiers = $modifiers & $forbiddenModifiers) {
      throw new Exception_ATABuilder("Method '$class::$methodName()' has forbidden modifiers $illegalModifiers.");
    }

    if ($requireOwnClass && $class !== $reflMethod->getDeclaringClass()->getName()) {
      throw new Exception_ATABuilder("Method '$class::$methodName()' is declared in a parent class, not here.");
    }

    return $reflMethod;
  }

}
