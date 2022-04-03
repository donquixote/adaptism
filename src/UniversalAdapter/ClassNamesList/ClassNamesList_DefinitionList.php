<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ClassNamesList;

use Donquixote\Adaptism\DefinitionList\DefinitionListInterface;

class ClassNamesList_DefinitionList implements ClassNamesListInterface {

  /**
   * @var \Donquixote\Adaptism\DefinitionList\DefinitionListInterface
   */
  private $definitionList;

  /**
   * @param \Donquixote\Adaptism\DefinitionList\DefinitionListInterface $definitionList
   */
  public function __construct(DefinitionListInterface $definitionList) {
    $this->definitionList = $definitionList;
  }

  /**
   * @return string[]
   */
  public function getClassNames(): array {
    return array_keys($this->definitionList->getDefinitionsByReturnType());
  }

}
