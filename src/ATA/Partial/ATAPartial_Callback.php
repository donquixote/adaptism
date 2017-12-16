<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class ATAPartial_Callback extends ATAPartial_CallbackBase1 {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface $argsMap
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    ArgsMapInterface $argsMap
  ) {
    $this->callback = $callback;
    parent::__construct($argsMap);
  }

  /**
   * @param mixed[] $args
   *
   * @return null|object
   *
   * @throws \Exception
   */
  protected function invokeArgs(array $args) {
    return $this->callback->invokeArgs($args);
  }
}
