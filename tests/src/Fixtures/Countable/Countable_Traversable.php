<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Countable;

use Donquixote\Adaptism\Annotation\Adapter;

/**
 * @Adapter
 */
class Countable_Traversable implements \Countable {

  /**
   * @var \Traversable
   */
  private $iterator;

  /**
   * @param \Traversable $iterator
   */
  public function __construct(\Traversable $iterator) {
    $this->iterator = $iterator;
  }

  /**
   * @return int
   */
  public function count(): int {
    return iterator_count($this->iterator);
  }
}
