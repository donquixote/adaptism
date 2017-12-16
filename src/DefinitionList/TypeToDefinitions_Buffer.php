<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\DefinitionList;

class TypeToDefinitions_Buffer implements TypeToDefinitionsInterface {

  /**
   * @var \Donquixote\Adaptism\DefinitionList\TypeToDefinitionsInterface
   */
  private $decorated;

  /**
   * @var array[][]
   *   Format: $[$returnTypeClassName][] = $definition
   */
  private $buffer = [];

  /**
   * @param \Donquixote\Adaptism\DefinitionList\TypeToDefinitionsInterface $decorated
   */
  public function __construct(TypeToDefinitionsInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $returnTypeClassName
   *
   * @return array[]
   *   Format: $[] = $definition
   */
  public function typeGetDefinitions(string $returnTypeClassName): array {

    return $this->buffer[$returnTypeClassName]
      ?? $this->buffer[$returnTypeClassName] = $this->decorated->typeGetDefinitions($returnTypeClassName);
  }
}
