<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ClassNamesList;

interface ClassNamesListInterface {

  /**
   * @return string[]
   */
  public function getClassNames(): array;

}
