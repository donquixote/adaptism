<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\PartialsList;

class PartialsList_KnownInstances implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][]
   *   Format: $[$classOrInterface][] = $partial
   */
  private $partialsByType;

  /**
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[] $partials
   *
   * @return self
   */
  public static function create(array $partials): self {

    $partialsByType = [];
    foreach ($partials as $partial) {
      $type = $partial->getResultType() ?? '?';
      $partialsByType[$type][] = $partial;
    }

    return new self($partialsByType);
  }

  /**
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][] $partialsByType
   *   Format: $[$classOrInterface][] = $partial
   */
  public function __construct(array $partialsByType) {
    $this->partialsByType = $partialsByType;
  }

  /**
   * @return string[]
   *   Format: $[$type] = $type
   */
  public function getTypes(): array {
    $dynamicTypes = array_keys($this->partialsByType);
    return array_combine($dynamicTypes, $dynamicTypes);
  }

  /**
   * @param string $classOrInterface
   *   Expected return type class name or interface name.
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function typeGetPartials($classOrInterface): array {
    return $this->partialsByType[$classOrInterface] ?? [];
  }
}
