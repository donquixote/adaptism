<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_Bridge implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface $first
   * @param class-string $bridgeType
   */
  public function __construct(
    private SpecificAdapterInterface $first,
    private string $bridgeType,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter
  ): ?object {
    static $recursion = 0;
    if ($recursion > 5) {
      return null;
    }
    ++$recursion;
    try {
      $bridgeObject = $this->first->adapt($adaptee, $this->bridgeType, $universalAdapter);
      if ($bridgeObject === null) {
        return null;
      }
      return $universalAdapter->adapt($bridgeObject, $resultType);
    }
    finally {
      --$recursion;
    }
  }

}
