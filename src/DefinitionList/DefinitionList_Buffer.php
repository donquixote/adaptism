<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\DefinitionList;

class DefinitionList_Buffer implements DefinitionListInterface, TypeToDefinitionsInterface {

  /**
   * @var \Donquixote\Adaptism\DefinitionList\DefinitionListInterface
   */
  private $decorated;

  /**
   * @var array[][]|null
   *   Format: $[$returnTypeClassName][] = $definition
   */
  private $buffer;

  /**
   * @param \Donquixote\Adaptism\DefinitionList\DefinitionListInterface $decorated
   */
  public function __construct(DefinitionListInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return array[][]
   *   Format: $[$returnTypeClassName][] = $definition
   */
  public function getDefinitionsByReturnType(): array {
    return $this->buffer
      ?? $this->buffer = $this->decorated->getDefinitionsByReturnType();
  }

  /**
   * @param string $returnTypeClassName
   *
   * @return array[]
   *   Format: $[] = $definition
   */
  public function typeGetDefinitions(string $returnTypeClassName): array {

    $map = $this->buffer
      ?? $this->buffer = $this->decorated->getDefinitionsByReturnType();

    return $map[$returnTypeClassName] ?? [];
  }
}
