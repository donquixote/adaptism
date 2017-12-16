<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

interface PartialsListInterface {

  /**
   * @param string $classOrInterface
   *   Expected return type class name or interface name.
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array;

  /**
   * @return string[]
   *   Format: $[$type] = $type
   */
  public function getTypes(): array;

}
