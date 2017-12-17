<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ArgsMap;

use Donquixote\Adaptism\ATA\ATAInterface;

class ArgsMap_MoreArgs implements ArgsMapInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface
   */
  private $decorated;

  /**
   * @var mixed[]
   */
  private $moreArgs;

  /**
   * @param \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface $decorated
   * @param mixed[] $moreArgs
   */
  public function __construct(ArgsMapInterface $decorated, $moreArgs) {
    $this->decorated = $decorated;
    $this->moreArgs = $moreArgs;
  }

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, ATAInterface $ata): ?array {

    if (null === $args = $this->decorated->buildArgs($original, $ata)) {
      return null;
    }

    foreach ($this->moreArgs as $i => $v) {
      $args[$i] = $v;
    }

    return $args;
  }
}
