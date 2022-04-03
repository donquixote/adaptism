<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Callback;

interface CallbackInterface {

  /**
   * @param ...$args
   *
   * @return mixed
   */
  public function __invoke(...$args): mixed;

}
