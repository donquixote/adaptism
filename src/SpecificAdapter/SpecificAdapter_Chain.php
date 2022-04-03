<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_Chain implements SpecificAdapterInterface {

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param mixed $adaptee
   * @param string $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function adapt(
    $adaptee,
    $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    foreach ($this->partials as $mapper) {
      if (NULL !== $candidate = $mapper->cast($adaptee, $interface, $universalAdapter)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return null;
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool {
    return TRUE;
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool {
    return TRUE;
  }

  /**
   * @return int
   */
  public function getSpecifity(): int {
    return 0;
  }

}
