<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_ValidateContract implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface $decorated
   */
  public function __construct(PartialsListInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[]
   */
  public function getTypes(): array {
    $types = $this->decorated->getTypes();
    foreach ($types as $type) {
      if (class_exists($type)) {
        continue;
      }
      if ('?' === $type || 'object' === $type) {
        continue;
      }
      $type_export = var_export($type, true);
      throw new \RuntimeException("Illegal type $type_export found.");
    }
    return $types;
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {
    $partials = $this->decorated->typeGetPartials($classOrInterface);
    return $partials;
  }
}
