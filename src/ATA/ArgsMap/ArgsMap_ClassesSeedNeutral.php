<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ArgsMap;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ArgsMap_ClassesSeedNeutral implements ArgsMapInterface {

  /**
   * @var string
   */
  private $sourceClass;

  /**
   * @var string[]
   */
  private $moreClasses;

  /**
   * @param string[] $classes
   */
  public function __construct($classes) {

    $this->sourceClass = array_shift($classes);

    $this->moreClasses = $classes;
  }

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, ATAInterface $ata): ?array {

    if (!$original instanceof $this->sourceClass) {
      return null;
    }

    $args = [$original];

    foreach ($this->moreClasses as $class) {
      if (ATAInterface::class === $class) {
        $args[] = $ata;
      }
      else {
        $args[] = $ata->adapt(new Seed_Neutral(), $class);
      }
    }

    return $args;
  }
}
