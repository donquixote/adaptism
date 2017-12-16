<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\DefinitionList;

use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class DefinitionList_ClassFilesIA implements DefinitionListInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var \Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface
   */
  private $classFileToOccurences;

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   * @param \Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface $classFileToOccurences
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, ClassFileToOccurencesInterface $classFileToOccurences) {
    $this->classFilesIA = $classFilesIA;
    $this->classFileToOccurences = $classFileToOccurences;
  }

  /**
   * @return array[][]
   *   Format: $[$returnTypeClassName][] = $definition
   */
  public function getDefinitionsByReturnType(): array {

    $definitions = [];
    foreach ($this->classFilesIA->withRealpathRoot() as $fileRealpath => $class) {
      foreach ($this->classFileToOccurences->classFileGetOccurences($class, $fileRealpath) as $occurence) {

        if (null === $returnTypeName = $occurence->getReturnTypeClassName()) {
          $returnTypeName = '?';
        }

        $definitions[$returnTypeName][] = $occurence->getDefinition();
      }
    }

    return $definitions;
  }
}
