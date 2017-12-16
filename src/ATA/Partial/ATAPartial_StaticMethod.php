<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface;

class ATAPartial_StaticMethod extends ATAPartial_CallbackBase1 {

  /**
   * @var \ReflectionMethod
   */
  private $method;

  /**
   * @param \ReflectionMethod $method
   * @param \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface $argsMap
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  public function __construct(
    \ReflectionMethod $method,
    ArgsMapInterface $argsMap,
    $sourceType = NULL,
    $resultType = NULL
  ) {
    $this->method = $method;
    parent::__construct($argsMap, $sourceType, $resultType);
  }

  /**
   * @param mixed[] $args
   *
   * @return null|object
   *
   * @throws \Exception
   */
  protected function invokeArgs(array $args) {
    return $this->method->invokeArgs(null, $args);
  }
}
