<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class SpecificAdapter_Seed_NeutralObject implements SpecificAdapterInterface {

  use ATAPartial_SpecifityTrait;

  /**
   * @var object
   */
  private $object;

  /**
   * @param object $object
   */
  public function __construct($object) {
    $this->object = $object;
  }

  /**
   * {@inheritdoc}
   */
  public function adapt(
    $adaptee,
    $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    if (!$adaptee instanceof Seed_Neutral) {
      return null;
    }

    if ($this->object instanceof $interface) {
      return $this->object;
    }

    return null;
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return \get_class($this->object);
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool {
    return $this->object instanceof $destinationInterface;
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
