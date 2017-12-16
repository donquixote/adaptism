<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery;

use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapter;
use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactories;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface;

class AdapterDiscovery_X implements AdapterDiscoveryInterface {

  /**
   * @var \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface
   */
  private $classFileToFactories;

  /**
   * @var \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface
   */
  private $factoryToAdapter;

  /**
   * @param \Donquixote\Adaptism\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {

    return new self(
      ClassFileToFactories::create(),
      FactoryToAdapter::create($paramToValue));
  }

  /**
   * @param \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface $classFileToFactories
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface $factoryToAdapter
   */
  public function __construct(
    ClassFileToFactoriesInterface $classFileToFactories,
    FactoryToAdapterInterface $factoryToAdapter
  ) {
    $this->classFileToFactories = $classFileToFactories;
    $this->factoryToAdapter = $factoryToAdapter;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA) {

    $partials = [];
    foreach ($classFilesIA->withRealpathRoot() as $fileRealpath => $class) {

      foreach ($this->classFileToFactories->classFileGetFactories($class, $fileRealpath) as $factory) {

        if (null === $partial = $this->factoryToAdapter->factoryGetPartial($factory)) {
          continue;
        }

        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
