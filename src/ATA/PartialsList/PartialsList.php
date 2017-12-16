<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

use Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesList_DefinitionList;
use Donquixote\Adaptism\ATA\DefinitionToATA\DefinitionToATA;
use Donquixote\Adaptism\DefinitionList\DefinitionList_Buffer;
use Donquixote\Adaptism\DefinitionList\DefinitionListInterface;
use Donquixote\Adaptism\DefinitionList\TypeToDefinitions_Buffer;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;

class PartialsList {

  /**
   * @param \Donquixote\Adaptism\DefinitionList\DefinitionListInterface $definitionList
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   *
   * @todo Insert cache layers!
   */
  public static function create(DefinitionListInterface $definitionList, ParamToValueInterface $paramToValue): PartialsListInterface {

    $definitionList = new DefinitionList_Buffer($definitionList);
    $typeToDefinitions = new TypeToDefinitions_Buffer($definitionList);
    $classNamesList = new ClassNamesList_DefinitionList($definitionList);

    $definitionToATA = DefinitionToATA::create($paramToValue);

    $list = new PartialsList_TypeToDefinitions(
      $typeToDefinitions,
      $classNamesList,
      $definitionToATA);

    $list = new PartialsList_Buffer($list);

    $list = new PartialsList_DynamicType($list);

    $list = new PartialsList_Upcast($list);

    $list = new PartialsList_Buffer($list);

    return $list;
  }

}
