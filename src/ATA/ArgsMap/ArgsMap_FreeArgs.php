<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ArgsMap;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ArgsMap_FreeArgs implements ArgsMapInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface
   */
  private $decorated;

  /**
   * @var string[]
   */
  private $moreClasses;

  /**
   * @param \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface $decorated
   * @param string[] $moreClasses
   */
  public function __construct(ArgsMapInterface $decorated, array $moreClasses) {
    $this->decorated = $decorated;
    $this->moreClasses = $moreClasses;
  }

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, ATAInterface $ata) {

    $args = $this->decorated->buildArgs($original, $ata);

    foreach ($this->moreClasses as $i => $class) {
      if (ATAInterface::class === $class) {
        $args[$i] = $ata;
      }
      else {
        $args[$i] = $ata->adapt(new Seed_Neutral(), $class);
      }
    }

    return $args;
  }
}
