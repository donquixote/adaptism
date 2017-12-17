<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

use Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesListInterface;
use Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface;
use Donquixote\Adaptism\DefinitionList\TypeToDefinitionsInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;

class PartialsList_TypeToDefinitions implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\DefinitionList\TypeToDefinitionsInterface
   */
  private $typeToDefinitions;

  /**
   * @var \Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesListInterface
   */
  private $classNamesList;

  /**
   * @var \Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface
   */
  private $definitionToATA;

  /**
   * @param \Donquixote\Adaptism\DefinitionList\TypeToDefinitionsInterface $typeToDefinitions
   * @param \Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesListInterface $classNamesList
   * @param \Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface $definitionToATA
   */
  public function __construct(
    TypeToDefinitionsInterface $typeToDefinitions,
    ClassNamesListInterface $classNamesList,
    DefinitionToATAInterface $definitionToATA
  ) {
    $this->typeToDefinitions = $typeToDefinitions;
    $this->classNamesList = $classNamesList;
    $this->definitionToATA = $definitionToATA;
  }

  /**
   * @return string[]
   *   Format: $[$type] = $type
   */
  public function getTypes(): array {
    $types = $this->classNamesList->getClassNames();
    return array_combine($types, $types);
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    $partials = [];
    foreach ($this->typeToDefinitions->typeGetDefinitions($classOrInterface) as $definition) {

      try {
        $partials[] = $this->definitionToATA->definitionGetPartial($definition);
      }
      catch (Exception_ATABuilder $e) {
        // @todo Log exception.
        unset($e);
      }
    }

    return $partials;
  }
}
