<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\PartialsList;

class PartialsList_Buffer implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\PartialsList\PartialsListInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][]
   *   Format: $[$classOrInterface][] = $ataPartial
   */
  private $buffer = [];

  /**
   * @var string[]|null
   */
  private $typesBuffer;

  /**
   * @param \Donquixote\Adaptism\UniversalAdapter\PartialsList\PartialsListInterface $decorated
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
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    return $this->buffer[$classOrInterface]
      ?? $this->buffer[$classOrInterface] = $this->decorated->typeGetPartials($classOrInterface);
  }
}
