<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA;

use Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesList_DefinitionList;
use Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATA;
use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_Buffer;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_DynamicType;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_Empty;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_KnownInstances;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_Multiple;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_TypeToDefinitions;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_Upcast;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList_ValidateContract;
use Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface;
use Donquixote\Adaptism\DefinitionList\DefinitionList_Buffer;
use Donquixote\Adaptism\DefinitionList\DefinitionList_ClassFilesIA;
use Donquixote\Adaptism\DefinitionList\DefinitionListInterface;
use Donquixote\Adaptism\DefinitionList\TypeToDefinitions_Buffer;
use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurences_BetterReflection;
use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface;
use Donquixote\Adaptism\ParamToValue\ParamToValue_Empty;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class ATABuilder {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[]
   */
  private $classFilesIAs = [];

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private $customPartials = [];

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface[]
   */
  private $customLists = [];

  /**
   * @var \Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface
   */
  private $classFileToOccurences;

  /**
   * @var \Donquixote\Adaptism\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @var bool
   */
  private $validate = false;

  /**
   * @return self
   */
  public static function create() {
    return new self(
      ClassFileToOccurences_BetterReflection::create());
  }

  /**
   * @param \Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesInterface $classFileToOccurences
   */
  public function __construct(ClassFileToOccurencesInterface $classFileToOccurences) {
    $this->classFileToOccurences = $classFileToOccurences;
    $this->paramToValue = new ParamToValue_Empty();
  }

  /**
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return static
   */
  public function withParamToValue(ParamToValueInterface $paramToValue): self {
    $clone = clone $this;
    $clone->paramToValue = $paramToValue;
    return $clone;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return static
   */
  public function withClassFilesIA(ClassFilesIAInterface $classFilesIA): self {
    $clone = clone $this;
    $clone->classFilesIAs[] = $classFilesIA;
    return $clone;
  }

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface $partial
   *
   * @return static
   */
  public function withCustomATA(ATAPartialInterface $partial): self {
    $clone = clone $this;
    $clone->customPartials[] = $partial;
    return $clone;
  }

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface $list
   *
   * @return static
   */
  public function withCustomList(PartialsListInterface $list): self {
    $clone = clone $this;
    $clone->customLists[] = $list;
    return $clone;
  }

  /**
   * @param bool $enabled
   *
   * @return static
   */
  public function withValidation($enabled = true): self {
    $clone = clone $this;
    $clone->validate = $enabled;
    return $clone;
  }

  /**
   * @return static
   */
  public function withFlush(): self {
    $builder = new self($this->classFileToOccurences);
    $builder->customLists = $this->buildPartialsLists();
    $builder->validate = $this->validate;
    return $builder;
  }

  /**
   * @return \Donquixote\Adaptism\ATA\ATAInterface
   */
  public function build(): ATAInterface {
    return new ATA_PartialsList($this->buildPartialsList());
  }

  /**
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  public function buildPartialsList(): PartialsListInterface {

    $lists = $this->buildPartialsLists();

    if ([] === $lists) {
      return new PartialsList_Empty();
    }

    if (1 === \count($lists)) {
      $list = reset($lists);
    }
    else {
      $list = new PartialsList_Multiple($lists);
      $list = $this->listAddValidatorIfEnabled($list);
    }

    $list = new PartialsList_DynamicType($list);
    $list = $this->listAddValidatorIfEnabled($list);

    $list = new PartialsList_Upcast($list);
    $list = $this->listAddValidatorIfEnabled($list);

    $list = new PartialsList_Buffer($list);
    $list = $this->listAddValidatorIfEnabled($list);

    return $list;
  }

  /**
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface[]
   */
  private function buildPartialsLists(): array {

    /** @var PartialsListInterface[] $lists */
    $lists = $this->customLists;

    if (null !== $list = $this->getPartialsListForDefinitions()) {
      $lists[] = $list;
    }

    if ([] !== $this->customPartials) {
      $lists[] = PartialsList_KnownInstances::create($this->customPartials);
    }

    foreach ($lists as &$thelist) {
      $thelist = $this->listAddValidatorIfEnabled($thelist);
    }
    unset($thelist);

    return $lists;
  }

  private function listAddValidatorIfEnabled(PartialsListInterface $list): PartialsListInterface {

    return $this->validate
      ? new PartialsList_ValidateContract($list)
      : $list;
  }

  /**
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsList_TypeToDefinitions|null
   */
  private function getPartialsListForDefinitions(): ?PartialsListInterface {

    if (null === $definitionList = $this->buildDefinitionList()) {
      return null;
    }

    $definitionList = new DefinitionList_Buffer($definitionList);
    $typeToDefinitions = new TypeToDefinitions_Buffer($definitionList);
    $classNamesList = new ClassNamesList_DefinitionList($definitionList);
    $definitionToATA = DefinitionToATA::create($this->paramToValue);

    $list = new PartialsList_TypeToDefinitions(
      $typeToDefinitions,
      $classNamesList,
      $definitionToATA);

    $list = new PartialsList_Buffer($list);

    return $list;
  }

  /**
   * @return \Donquixote\Adaptism\DefinitionList\DefinitionListInterface|null
   */
  private function buildDefinitionList(): ?DefinitionListInterface {

    $lists = [];

    if (null !== $classFilesIA = $this->getClassFilesIA()) {
      $lists[] = new DefinitionList_ClassFilesIA(
        $classFilesIA,
        $this->classFileToOccurences);
    }

    if ([] === $lists) {
      return null;
    }

    // @todo Combine if more than one?
    return reset($lists);
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface|null
   */
  private function getClassFilesIA(): ?ClassFilesIAInterface {

    if ([] === $this->classFilesIAs) {
      return null;
    }

    if (1 === \count($this->classFilesIAs)) {
      return reset($this->classFilesIAs);
    }

    return new ClassFilesIA_Multiple($this->classFilesIAs);
  }

}
