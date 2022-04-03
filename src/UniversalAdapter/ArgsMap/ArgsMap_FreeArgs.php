<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ArgsMap;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ArgsMap_FreeArgs implements ArgsMapInterface {

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

    foreach ($this->moreClasses as $i => $class) {
      if (UniversalAdapterInterface::class === $class) {
        $args[$i] = $ata;
      }
      else {
        $args[$i] = $ata->adapt(new Seed_Neutral(), $class);
      }
    }

    return $args;
  }
}
