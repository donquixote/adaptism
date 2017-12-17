<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Countable;

class Countable_Callback implements \Countable {

  /**
   * @var callable
   */
  private $callable;

  /**
   * @param callable $callable
   */
  public function __construct($callable) {

    if (!\is_callable($callable)) {
      throw new \InvalidArgumentException("First argument must be a callable.");
    }

    $this->callable = $callable;
  }

  /**
   * @return int
   */
  public function count(): int {

    $n = \call_user_func($this->callable);

    if (!\is_int($n)) {
      throw new \RuntimeException("Callback must return an integer.");
    }

    if ($n < 0) {
      throw new \RuntimeException("Callback must return a non-negative integer.");
    }

    return $n;
  }
}
