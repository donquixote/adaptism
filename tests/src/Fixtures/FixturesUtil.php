<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

use Donquixote\Adaptism\ATA\ATA_PartialsList;
use Donquixote\Adaptism\ATA\PartialsList\PartialsList;
use Donquixote\Adaptism\DefinitionList\DefinitionList_ClassFilesIA;
use Donquixote\Adaptism\Discovery\AdapterDiscovery;
use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurences_BetterReflection;
use Donquixote\Adaptism\ParamToValue\ParamToValue_Empty;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;

class FixturesUtil {

  /**
   * @return \Donquixote\Adaptism\ATA\ATAInterface
   */
  public static function getATA() {
    return new ATA_PartialsList(self::getPartialsList());
  }

  /**
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  public static function getPartialsList() {

    $paramToValue = new ParamToValue_Empty();

    return PartialsList::create(
      self::getDefinitionList(),
      $paramToValue);
  }

  /**
   * @return \Donquixote\Adaptism\DefinitionList\DefinitionList_ClassFilesIA
   */
  public static function getDefinitionList() {

    $classFileToOccurences = ClassFileToOccurences_BetterReflection::create();

    return new DefinitionList_ClassFilesIA(
      self::getClassFilesIA(),
      $classFileToOccurences);
  }

  /**
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public static function discoverPartials() {

    $paramToValue = new ParamToValue_Empty();

    return AdapterDiscovery::create($paramToValue)
      ->classFilesIAGetPartials(
        self::getClassFilesIA());
  }

  /**
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function getClassFilesIA() {
    return ClassFilesIA_NamespaceDirectoryPsr4::create(
      __DIR__,
      __NAMESPACE__);
  }

}
