<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ClassNamesList;

interface ClassNamesListInterface {

  /**
   * @return string[]
   */
  public function getClassNames(): array;

}
