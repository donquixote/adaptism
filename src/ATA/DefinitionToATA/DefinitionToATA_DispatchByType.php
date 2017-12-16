<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\DefinitionToATA;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;

class DefinitionToATA_DispatchByType implements DefinitionToATAInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface[]
   */
  private $definitionToATAs;

  /**
   * @var string
   */
  private $typeKey;

  /**
   * @param \Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATAInterface[] $definitionToATAs
   * @param string $typeKey
   */
  public function __construct(array $definitionToATAs, $typeKey = 'type') {
    $this->definitionToATAs = $definitionToATAs;
    $this->typeKey = $typeKey;
  }

  /**
   * @param array $definition
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function definitionGetPartial(array $definition): ATAPartialInterface {

    if (null === $type = $definition[$this->typeKey] ?? null) {
      throw new Exception_ATABuilder("No type found in definition.");
    }

    if (!\is_string($type) && !\is_int($type)) {
      $typeOfType = \gettype($type);
      throw new Exception_ATABuilder("\$definition['$this->typeKey'] must be a string or integer, $typeOfType provided instead.");
    }

    if (null === $definitionToATA = $this->definitionToATAs[$type] ?? null) {
      throw new Exception_ATABuilder("Unknown type '$type' specified in definition.");
    }

    return $definitionToATA->definitionGetPartial($definition);
  }
}
