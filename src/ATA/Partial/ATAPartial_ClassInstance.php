<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface;

class ATAPartial_ClassInstance extends ATAPartial_CallbackBase1 {

  /**
   * @var \ReflectionClass
   */
  private $reflClass;

  /**
   * @param \ReflectionClass $reflClass
   * @param \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface $argsMap
   * @param string|null $sourceType
   */
  public function __construct(
    \ReflectionClass $reflClass,
    ArgsMapInterface $argsMap,
    ?string $sourceType = NULL
  ) {
    $this->reflClass = $reflClass;
    parent::__construct(
      $argsMap,
      $sourceType,
      $reflClass->getName());
  }

  /**
   * @param mixed[] $args
   *
   * @return null|object
   *
   * @throws \Exception
   */
  protected function invokeArgs(array $args) {
    return $this->reflClass->newInstanceArgs($args);
  }
}
