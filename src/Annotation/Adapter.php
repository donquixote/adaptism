<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Annotation;

use Donquixote\Annotation\Value\ClassedAnnotation\ClassedAnnotationInterface;

/**
 * @Annotation
 */
final class Adapter implements ClassedAnnotationInterface {

  /**
   * @param array $values
   * @param \Reflector $reflector
   *
   * @return self
   */
  public static function create(array $values, \Reflector $reflector): self {
    // For now we assume this annotation won't use any arguments.
    return new self();
  }
}
