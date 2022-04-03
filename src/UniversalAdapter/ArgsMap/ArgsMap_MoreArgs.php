<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ArgsMap;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class ArgsMap_MoreArgs implements ArgsMapInterface {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface
   */
  private $decorated;

  /**
   * @var mixed[]
   */
  private $moreArgs;

  /**
   * @param \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface $decorated
   * @param mixed[] $moreArgs
   */
  public function __construct(ArgsMapInterface $decorated, $moreArgs) {
    $this->decorated = $decorated;
    $this->moreArgs = $moreArgs;
  }

  /**
   * @param object $original
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return mixed[]|null
   */
  public function buildArgs($original, UniversalAdapterInterface $ata): ?array {

    if (null === $args = $this->decorated->buildArgs($original, $ata)) {
      return null;
    }

    foreach ($this->moreArgs as $i => $v) {
      $args[$i] = $v;
    }

    return $args;
  }
}
