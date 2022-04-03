<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ReflectionCallback;

interface ReflectionCallbackInterface {

  /**
   * Returns an array of parameter attributes.
   *
   * @param string|null $name
   *   Name of an attribute class
   * @param int $flags
   *   Flags to control how attributes are filtered.
   *
   * @return \ReflectionAttribute[]
   *
   * @see \ReflectionClass::getAttributes()
   * @see \ReflectionAttribute::IS_INSTANCEOF
   */
  public function getAttributes(?string $name = null, int $flags = 0): array;

  /**
   * @return \ReflectionParameter[]
   */
  public function getParameters(): array;

  /**
   * @return \ReflectionType
   */
  public function getReturnType(): \ReflectionType;

  /**
   * @return callable
   */
  public function getCallable(): callable;

}
