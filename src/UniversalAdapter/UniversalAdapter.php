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
    object $original,
    string $destinationInterface,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object {

    if ($original instanceof $destinationInterface) {
      return $original;
    }

    return $this->specificAdapter->adapt(
      $original,
      $destinationInterface,
      $universalAdapter ?? $this,
    );
  }
}
