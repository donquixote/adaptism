<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\ClassFileToAdapters;

interface ClassFileToAdaptersInterface {

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array;
}
