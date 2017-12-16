<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

use phpDocumentor\Reflection\Types\Context;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;

/**
 * @see \Roave\BetterReflection\BetterReflection
 */
class BetterReflectionUtil {

  /**
   * @param \Roave\BetterReflection\Reflection\ReflectionClass $class
   *
   * @return \phpDocumentor\Reflection\Types\Context
   */
  public static function classGetContext(ReflectionClass $class): Context {

    $contextFinder = new NamespaceNodeToReflectionTypeContext();
    $namespaceNode = $class->getDeclaringNamespaceAst();

    return $contextFinder->__invoke($namespaceNode);
  }

}
