<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Rgb;

class RgbColor implements RgbColorInterface {

  public function __construct(
    private int $r,
    private int $g,
    private int $b,
  ) {}

  /**
   * @return int
   */
  public function red(): int {
    return $this->r;
  }

  /**
   * @return int
   */
  public function green(): int {
    return $this->g;
  }

  /**
   * @return int
   */
  public function blue(): int {
    return $this->b;
  }
}
