<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_Buffer implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$classOrInterface][] = $ataPartial
   */
  private $buffer = [];

  /**
   * @var string[]|null
   */
  private $typesBuffer;

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface $decorated
   */
  public function __construct(PartialsListInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[]
   *   Format: $[$type] = $type
   */
  public function getTypes(): array {
    return $this->typesBuffer
      ?? $this->typesBuffer = $this->decorated->getTypes();
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    return $this->buffer[$classOrInterface]
      ?? $this->buffer[$classOrInterface] = $this->decorated->typeGetPartials($classOrInterface);
  }
}
