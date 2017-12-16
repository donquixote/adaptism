<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

trait ATAPartial_SpecifityTrait {

  /**
   * @var int
   */
  private $specifity = 0;

  /**
   * @param int $specifity
   *
   * @return static
   */
  public function withSpecifity($specifity) {

    if ($specifity === $this->specifity) {
      return $this;
    }

    $clone = clone $this;
    $clone->specifity = $specifity;
    return $clone;
  }

  /**
   * @return int
   */
  public function getSpecifity() {
    return $this->specifity;
  }

}
