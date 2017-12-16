<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\DefinitionList;

interface TypeToDefinitionsInterface {

  /**
   * @param string $returnTypeClassName
   *
   * @return array[]
   *   Format: $[] = $definition
   */
  public function typeGetDefinitions(string $returnTypeClassName): array;

}
