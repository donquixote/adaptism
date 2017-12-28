<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA;

interface ATAInterface {

  /**
   * @param object $original
   * @param string $destinationInterface
   *
   * @return object|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   */
  public function adapt($original, string $destinationInterface);

}
