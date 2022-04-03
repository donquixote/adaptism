<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

interface AdapterDiscoveryInterface {

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA): array;
}
