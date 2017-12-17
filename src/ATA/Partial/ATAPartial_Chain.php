<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;

class ATAPartial_Chain implements ATAPartialInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Adaptism\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function adapt(
    $source,
    $interface,
    ATAInterface $helper
  ) {

    foreach ($this->partials as $mapper) {
      if (NULL !== $candidate = $mapper->cast($source, $interface, $helper)) {
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
