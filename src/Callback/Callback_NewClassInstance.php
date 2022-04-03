<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Callback;

class Callback_NewClassInstance implements CallbackInterface {

  public function __construct(
    private string $class,
  ) {}

  /**
   * @param ...$args
   *
   * @return mixed
   */
  public function __invoke(...$args): mixed {
    return new ($this->class)(...$args);
  }

}
