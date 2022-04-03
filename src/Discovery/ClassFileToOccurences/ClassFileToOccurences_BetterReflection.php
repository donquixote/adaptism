<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\ClassFileToOccurences;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Adaptism\Discovery\Occurence\Occurence;
use Donquixote\Adaptism\Util\BetterReflectionUtil;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

class ClassFileToOccurences_BetterReflection implements ClassFileToOccurencesInterface {

  /**
   * @var \Roave\BetterReflection\Reflector\ClassReflector
   */
  private $classReflector;

  /**
   * @return self
   */
  public static function create(): self {
    return new self(
      (new BetterReflection())->classReflector());
  }

  /**
   * @param \Roave\BetterReflection\Reflector\ClassReflector $classReflector
   */
  public function __construct(ClassReflector $classReflector) {
    $this->classReflector = $classReflector;
  }

  /**
   * @param string $class
   * @param string $fileRealpath
   *   Path to an existing and readable class file, that is likely to define the class.
   *
   * @return \Donquixote\Adaptism\Discovery\Occurence\Occurence[]
   *
   * @see \Donquixote\Adaptism\UniversalAdapter\DefinitionToATA\DefinitionToATA
   */
  public function classFileGetOccurences($class, $fileRealpath): array {

    $fileContents = file_get_contents($fileRealpath);

    if (!ClassFileToOccurencesUtil::fileContentMightHaveAnnotation($fileContents, Adapter::class)) {
      return [];
    }

    try {
      $reflClass = $this->classReflector->reflect($class);
    }
    catch (IdentifierNotFound $e) {
      unset($e);
      return [];
    }

    if ($reflClass->getFileName() !== $fileRealpath) {
      return [];
    }

    $context = BetterReflectionUtil::classGetContext($reflClass);

    $pattern = ClassFileToOccurencesUtil::contextBuildPattern(
      $context,
      Adapter::class);

    $occurences = [];

    if (null !== $occurence = $this->classGetOccurence($reflClass, $pattern)) {
      $occurences[] = $occurence;
    }

    foreach ($reflClass->getMethods(\ReflectionMethod::IS_STATIC) as $reflMethod) {
      foreach ($this->methodGetOccurences($reflMethod, $pattern) as $occurence) {
        $occurences[] = $occurence;
      }
    }

    return $occurences;
  }



  /**
   * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflClass
   * @param string $pattern
   *
   * @return \Donquixote\Adaptism\Discovery\Occurence\Occurence|null
   *
   * @see \Donquixote\Adaptism\UniversalAdapter\DefinitionToATA\DefinitionToATA_AdapterClass
   * @see \Donquixote\Adaptism\UniversalAdapter\DefinitionToATA\DefinitionToATA_ATAClass
   */
  private function classGetOccurence(ReflectionClass $reflClass, $pattern): ?Occurence {

    if (!$reflClass->isInstantiable()) {
      return null;
    }

    if ('' === $docComment = $reflClass->getDocComment()) {
      return null;
    }

    if (!preg_match($pattern, $docComment)) {
      return null;
    }

    if (!$reflClass->hasMethod('__construct')) {
      return null;
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
    $constructor = $reflClass->getConstructor();

    if ($reflClass->implementsInterface(SpecificAdapterInterface::class)) {
      return Occurence::fromClassName($reflClass->getName(), 'ata');
    }

    $parameters = $constructor->getParameters();

    if (null === $parameter = $parameters[0] ?? null) {
      return null;
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
    if (null === $parameter->getClass()) {
      return null;
    }

    return Occurence::fromClassName($reflClass->getName(), 'adapter');
  }

  /**
   * @param \Roave\BetterReflection\Reflection\ReflectionMethod $reflMethod
   * @param string $pattern
   *
   * @return \Donquixote\Adaptism\Discovery\Occurence\Occurence[]
   */
  private function methodGetOccurences(ReflectionMethod $reflMethod, string $pattern): array {

    if ($reflMethod->isAbstract()) {
      return [];
    }

    if (!$reflMethod->isPublic()) {
      return [];
    }

    if (!$reflMethod->isStatic()) {
      return [];
    }

    if ('' === $docComment = $reflMethod->getDocComment()) {
      return [];
    }

    if (!preg_match($pattern, $docComment)) {
      return [];
    }

    $occurences = [];
    foreach (ClassFileToOccurencesUtil::functionGetReturnTypeClassNames($reflMethod) as $className) {
      if (null !== $occurence = $this->typeBuildFunctionOccurence($className, $reflMethod)) {
        $occurences[] = $occurence;
      }
    }

    return $occurences;
  }

  /**
   * @param string $className
   * @param \Roave\BetterReflection\Reflection\ReflectionMethod $reflMethod
   *
   * @return \Donquixote\Adaptism\Discovery\Occurence\Occurence|null
   */
  private function typeBuildFunctionOccurence(string $className, ReflectionMethod $reflMethod): ?Occurence {

    if ('object' !== $className
      && is_a($className, SpecificAdapterInterface::class, true)
    ) {
      return Occurence::fromStaticMethod(
        $reflMethod->getDeclaringClass()->getName(),
        $reflMethod->getShortName(),
        'ata');
    }

    $parameters = $reflMethod->getParameters();

    if (null === $parameter = $parameters[0] ?? null) {
      return null;
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
    if (null === $parameter->getClass()) {
      return null;
    }

    return Occurence::fromStaticMethod(
      $reflMethod->getDeclaringClass()->getName(),
      $reflMethod->getShortName(),
      'adapter')
      ->withReturnTypeClassName($className);
  }

}
