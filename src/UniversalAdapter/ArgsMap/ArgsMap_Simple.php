<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ArgsMap;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class ArgsMap_Simple implements ArgsMapInterface {

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, UniversalAdapterInterface $ata): ?array {

    return [$original];
  }
}
