<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ArgsMap;

use Donquixote\Adaptism\ATA\ATAInterface;

class ArgsMap_SimpleWithATA implements ArgsMapInterface {

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, ATAInterface $ata): ?array {

    return [$original, $ata];
  }
}
