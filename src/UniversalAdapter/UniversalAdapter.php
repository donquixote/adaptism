<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;

class UniversalAdapter implements UniversalAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface $specificAdapter
   */
  public function __construct(
    private SpecificAdapterInterface $specificAdapter,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object {

    if ($adaptee instanceof $resultType) {
      return $adaptee;
    }

    return $this->specificAdapter->adapt(
      $adaptee,
      $resultType,
      $universalAdapter ?? $this,
    );
  }
}
