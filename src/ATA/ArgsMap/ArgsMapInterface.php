<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ArgsMap;

use Donquixote\Adaptism\ATA\ATAInterface;

interface ArgsMapInterface {

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, ATAInterface $ata);

}
