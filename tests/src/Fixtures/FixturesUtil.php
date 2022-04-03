<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery;
use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Donquixote\Adaptism\AdapterMap\AdapterMap_DefinitionList;
use Donquixote\Adaptism\AdapterMap\AdapterMapInterface;
use Donquixote\Adaptism\Discovery\AdapterDiscovery;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_DispatchByType;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Psr\Container\ContainerInterface;

class FixturesUtil {

  public static function getContainer(array $objects): ContainerInterface {
    return new class($objects) implements ContainerInterface {
      public function __construct(
        private array $objects,
      ) {}

      public function get(string $id) {
        return $this->objects[$id] ?? null;
      }

      public function has(string $id): bool {
        return isset($this->objects[$id]);
      }
    };
  }

  public static function getUniversalAdapter(ContainerInterface $container = null): UniversalAdapterInterface {
    return new UniversalAdapter(
      new SpecificAdapter_DispatchByType(
        self::getAdapterMap(
          $container ?? self::getContainer([]),
        ),
      ),
    );
  }

  public static function getAdapterMap(ContainerInterface $container): AdapterMapInterface {
    return new AdapterMap_DefinitionList(
      self::getDefinitionList(),
      $container,
    );
  }

  public static function getDefinitionList(): AdapterDefinitionListInterface {
    return new AdapterDefinitionList_Discovery(
      new ReflectionClassesIA_ClassFilesIA(
        self::getClassFilesIA(),
      ),
    );
  }

  public static function getClassFilesIA(): ClassFilesIAInterface {
    return ClassFilesIA_NamespaceDirectoryPsr4::create(
      __DIR__,
      __NAMESPACE__);
  }

}
