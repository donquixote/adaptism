<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_DynamicType implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$class][] = $ataPartial
   */
  private $dynamicTypeBuffer;

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
    $types = $this->decorated->getTypes();
    unset($types['?']);
    $dynamicTypes = array_keys($this->getDynamicPartialsByType());
    return $types + array_combine($dynamicTypes, $dynamicTypes);
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    if ('?' === $classOrInterface) {
      return [];
    }

    $dynamicTypeBuffer = $this->getDynamicPartialsByType();

    $partials = $this->decorated->typeGetPartials($classOrInterface);

    foreach ($dynamicTypeBuffer[$classOrInterface] ?? [] as $partial) {
      $partials[] = $partial;
    }

    return $partials;
  }

  /**
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$class][] = $ataPartial
   */
  private function getDynamicPartialsByType(): array {

    return $this->dynamicTypeBuffer
      ?? $this->dynamicTypeBuffer = $this->buildDynamicTypeBuffer();
  }

  /**
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$class][] = $ataPartial
   */
  private function buildDynamicTypeBuffer(): array {

    $buffer = [];
    foreach ($this->decorated->typeGetPartials('?') as $partial) {

      $type = $partial->getResultType() ?? 'object';

      $buffer[$type][] = $partial;
    }

    return $buffer;
  }
}
