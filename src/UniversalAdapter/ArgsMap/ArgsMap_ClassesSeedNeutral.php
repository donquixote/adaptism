<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ArgsMap;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
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
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, UniversalAdapterInterface $ata): ?array {

    if (!$original instanceof $this->sourceClass) {
      return null;
    }

    $args = [$original];

    foreach ($this->moreClasses as $class) {
      if (UniversalAdapterInterface::class === $class) {
        $args[] = $ata;
      }
      else {
        $args[] = $ata->adapt(new Seed_Neutral(), $class);
      }
    }

    return $args;
  }
}
