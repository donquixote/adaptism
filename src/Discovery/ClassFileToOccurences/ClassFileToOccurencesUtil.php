<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\ClassFileToOccurences;

use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Self_;
use PhpParser\Node\Stmt\Static_;
use Roave\BetterReflection\Reflection\ReflectionFunctionAbstract;
use Roave\BetterReflection\Reflection\ReflectionMethod;

class ClassFileToOccurencesUtil {

  /**
   * @param \Roave\BetterReflection\Reflection\ReflectionFunctionAbstract $reflFunction
   *
   * @return string[]
   */
  public static function functionGetReturnTypeClassNames(ReflectionFunctionAbstract $reflFunction): array {

    if (null !== $returnType = $reflFunction->getReturnType()) {

      $name = $returnType->__toString();

      if ($returnType->isBuiltin()) {

        if ('object' !== $name) {
          return [];
        }

        return ['object'];
      }

      if ('self' === $name || 'static' === $name) {
        if (!$reflFunction instanceof ReflectionMethod) {
          return [];
        }

        return $reflFunction->getDeclaringClass()->getName();
      }

      return [];
    }

    if ([] !== $docReturnTypes = $reflFunction->getDocBlockReturnTypes()) {

      $names = [];
      foreach ($docReturnTypes as $docReturnType) {

        if (!$docReturnType instanceof Object_) {
          if ($docReturnType instanceof Self_ || $docReturnType instanceof Static_) {
            if (!$reflFunction instanceof ReflectionMethod) {
              continue;
            }

            $names[] = $reflFunction->getDeclaringClass()->getName();

          }

          continue;
        }

        if (null !== $fqsen = $docReturnType->getFqsen()) {
          // Remove the starting "\\".
          $names[] = substr($fqsen->getName(), 1);
        }
        else {
          $names[] = 'object';
        }
      }

      return $names;
    }

    return [];
  }

  /**
   * @param string $fileContents
   * @param string $class
   *
   * @return bool
   *
   * @todo Move to utility class.
   */
  public static function fileContentMightHaveAnnotation($fileContents, $class): bool {

    if (false !== strpos($fileContents, $class)) {
      return true;
    }

    $nspos = 0;
    while (false !== $nspos = strpos($class, '\\', $nspos + 1)) {

      $relativeClassName = substr($class, $nspos + 1);

      if (false === strpos($fileContents, '@' . $relativeClassName)) {
        continue;
      }

      $namespace = substr($class, 0, $nspos);

      if (!preg_match('~[\s;]namespace\s+' . preg_quote($namespace, '~') . '\s*;~', $fileContents)) {
        continue;
      }

      return true;
    }

    return false;
  }

  /**
   * @param \phpDocumentor\Reflection\Types\Context $context
   * @param string $class
   *
   * @return string
   */
  public static function contextBuildPattern(Context $context, string $class): string {

    $adapterAnnotationAliases = array_keys(
      $context->getNamespaceAliases(),
      $class);

    $adapterAnnotationAliases[] = '\\' . $class;

    $patternFragments = [];
    foreach ($adapterAnnotationAliases as $alias) {
      $patternFragments[] = preg_quote($alias, '~');
    }

    return '~(?:|\h*\*|^/\*\*)\s+@(' . implode('|', $patternFragments) . ')[\s(]~';
  }

}
