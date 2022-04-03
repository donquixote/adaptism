<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Countable;

use Donquixote\Adaptism\Attribute\Adaptee;
use Donquixote\Adaptism\Attribute\Adapter;

#[Adapter]
class Countable_Traversable implements \Countable {

  /**
   * @param \Traversable $iterator
   */
  public function __construct(
    #[Adaptee] private \Traversable $iterator,
  ) {}

  /**
   * @return int
   */
  public function count(): int {
    return \iterator_count($this->iterator);
  }
}
