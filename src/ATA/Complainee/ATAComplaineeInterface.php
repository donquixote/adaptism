<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Complainee;

interface ATAComplaineeInterface {

  /**
   * @param object $original
   * @param string $destinationInterface
   * @param object $instead
   */
  public function complain($original, $destinationInterface, $instead);

}
