<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Rgb;

class RgbColor implements RgbColorInterface {

  /**
   * @var int
   */
  private $r;

  /**
   * @var int
   */
  private $g;

  /**
   * @var int
   */
  private $b;

  /**
   * @param int $r
   * @param int $g
   * @param int $b
   */
  public function __construct($r, $g, $b) {
    $this->r = $r;
    $this->g = $g;
    $this->b = $b;
  }

  /**
   * @return int
   */
  public function red() {
    return $this->r;
  }

  /**
   * @return int
   */
  public function green() {
    return $this->g;
  }

  /**
   * @return int
   */
  public function blue() {
    return $this->b;
  }
}
