<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\PartialsList;

class PartialsList_Empty implements PartialsListInterface {

  /**
   * @return string[]
   */
  public function getTypes(): array {
    return [];
  }

  /**
   * @param string $classOrInterface
   *   Expected return type class name or interface name.
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function typeGetPartials($classOrInterface): array {
    return [];
  }
}
