<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\ClassFileToOccurences;

interface ClassFileToOccurencesInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *   Path to an existing and readable class file, that is likely to define
   *   the class.
   *
   * @return \Donquixote\Adaptism\Discovery\Occurence\Occurence[]
   */
  public function classFileGetOccurences($class, $fileRealpath): array;
}
