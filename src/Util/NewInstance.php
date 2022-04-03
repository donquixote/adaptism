<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Util;

class NewInstance {

  public static function __callStatic(string $class, array $args) {
    return new $class(...$args);
  }

}
