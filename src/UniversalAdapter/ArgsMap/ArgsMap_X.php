<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ArgsMap;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ArgsMap_X implements ArgsMapInterface {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface
   */
  private $decorated;

  /**
   * @var string[]
   */
  private $moreClasses;

  /**
   * @param \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface $decorated
   * @param string[] $moreClasses
   */
  public function __construct(ArgsMapInterface $decorated, array $moreClasses) {
    $this->decorated = $decorated;
    $this->moreClasses = $moreClasses;
  }

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, UniversalAdapterInterface $ata): ?array {

    $args = $this->decorated->buildArgs($original, $ata);

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
