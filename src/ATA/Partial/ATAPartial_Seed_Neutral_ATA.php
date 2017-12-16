<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ATAPartial_Seed_Neutral_ATA implements ATAPartialInterface {

  use ATAPartial_SpecifityTrait;

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

    if ($interface !== ATAInterface::class) {
      return null;
    }

    return $ata;
  }

  public function getResultType(): ?string {
    return ATAInterface::class;
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface) {
    return $destinationInterface === ATAInterface::class;
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass) {
    return Seed_Neutral::class;
  }
}
