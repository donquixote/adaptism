<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ATASpecific_Seed_Neutral_Adapter implements SpecificAdapterInterface {

  use ATAPartial_SpecifityTrait;

  /**
   * @param object $adaptee
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

    if (!$adaptee instanceof Seed_Neutral) {
      return null;
    }

    if ($interface !== UniversalAdapterInterface::class) {
      return null;
    }

    return $universalAdapter;
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return UniversalAdapterInterface::class;
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool {
    return $destinationInterface === UniversalAdapterInterface::class;
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool {
    return is_a($sourceClass, Seed_Neutral::class, true);
  }
}
