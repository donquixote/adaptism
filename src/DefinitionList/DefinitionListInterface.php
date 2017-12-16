<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\DefinitionList;

interface DefinitionListInterface {

  /**
   * @return array[][]
   *   Format: $[$returnTypeClassName][] = $definition
   */
  public function getDefinitionsByReturnType(): array;

}
