<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\Seed\Seed_Neutral;

class ATAPartial_Seed_Neutral_Objects implements ATAPartialInterface {

  use ATAPartial_SpecifityTrait;

  /**
   * @var object[]
   */
  private $objectsByClassName = [];

  /**
   * @param array $objects
   */
  public function __construct(array $objects) {

    /** @var object[][] $map */
    $map = [];
    foreach ($objects as $object) {

      $class = \get_class($object);
      $reflClass = new \ReflectionClass($class);

      foreach ($reflClass->getInterfaceNames() as $interfaceName) {
        $this->objectsByClassName[$interfaceName] = $object;
      }

      $i = 0;
      do {
        $map[$i][$reflClass->getName()] = $object;
        ++$i;
      }
      while ($reflClass = $reflClass->getParentClass());
    }

    arsort($map);

    foreach ($map as $floor) {
      foreach ($floor as $name => $object) {
        $this->objectsByClassName[$name] = $object;
      }
    }
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

    return $this->objectsByClassName[$interface] ?? null;
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return 'object';
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool {
    return isset($this->objectsByClassName[$destinationInterface]);
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool {
    // The class is final, so..
    return Seed_Neutral::class === $sourceClass;
  }
}
