<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ATAPartial_Seed_Neutral_Object implements ATAPartialInterface {

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
   * @param object $original
   * @param string $interface
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function adapt(
    $original,
    $interface,
    ATAInterface $ata
  ) {

    if (!$original instanceof Seed_Neutral) {
      return null;
    }

    if ($this->object instanceof $interface) {
      return $this->object;
    }

    return null;
  }

  public function getResultType(): ?string {
    return \get_class($this->object);
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface) {
    return $this->object instanceof $destinationInterface;
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass) {
    return is_a($sourceClass, Seed_Neutral::class, true);
  }
}
