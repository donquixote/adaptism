<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;

interface ATAPartialInterface {

  /**
   * @param object $original
   * @param string $interface
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  public function adapt(
    $original,
    $interface,
    ATAInterface $ata);

  /**
   * Gets the result type, or a common ancestor of all result types.
   *
   * @return string|null
   */
  public function getResultType(): ?string;

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool;

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool;

  /**
   * @return int
   */
  public function getSpecifity(): int;

}
