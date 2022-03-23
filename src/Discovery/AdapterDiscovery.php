<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery;

use Donquixote\Adaptism\Discovery\ClassFileToAdapters\ClassFileToAdapters;
use Donquixote\Adaptism\Discovery\ClassFileToAdapters\ClassFileToAdaptersInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class AdapterDiscovery implements AdapterDiscoveryInterface {

  /**
   * @var ClassFileToAdaptersInterface
   */
  private $classFileToAdapters;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    return new self(
      ClassFileToAdapters::create($paramToValue));
  }

  /**
   * @param ClassFileToAdaptersInterface $classFileToAdapters
   */
  public function __construct(ClassFileToAdaptersInterface $classFileToAdapters) {
    $this->classFileToAdapters = $classFileToAdapters;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA): array {

    $partials = [];
    foreach ($classFilesIA->withRealpathRoot() as $fileRealpath => $class) {
      foreach ($this->classFileToAdapters->classFileGetPartials($class, $fileRealpath) as $partial) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
