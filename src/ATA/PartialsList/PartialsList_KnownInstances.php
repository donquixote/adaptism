<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_KnownInstances implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$classOrInterface][] = $partial
   */
  private $partialsByType;

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[] $partials
   *
   * @return self
   */
  public static function create(array $partials) {

    $partialsByType = [];
    foreach ($partials as $partial) {
      $type = $partial->getResultType() ?? '?';
      $partialsByType[$type][] = $partial;
    }

    return new self($partialsByType);
  }

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][] $partialsByType
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
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {
    return $this->partialsByType[$classOrInterface] ?? [];
  }
}
