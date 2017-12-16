<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery;

use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapter;
use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface;
use Donquixote\Adaptism\Discovery\FactoryVisitor\FactoryVisitor_CollectAdapters;
use Donquixote\Adaptism\ParamToValue\ParamToValueInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\FactoryDiscovery\Discovery\FactoryDiscovery;
use Donquixote\FactoryDiscovery\Discovery\FactoryDiscoveryInterface;

class AdapterDiscovery_Visitor implements AdapterDiscoveryInterface {

  /**
   * @var \Donquixote\FactoryDiscovery\Discovery\FactoryDiscoveryInterface
   */
  private $factoryDiscovery;

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
      FactoryDiscovery::create(),
      FactoryToAdapter::create($paramToValue));
  }

  /**
   * @param \Donquixote\FactoryDiscovery\Discovery\FactoryDiscoveryInterface $factoryDiscovery
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface $factoryToAdapter
   */
  public function __construct(
    FactoryDiscoveryInterface $factoryDiscovery,
    FactoryToAdapterInterface $factoryToAdapter
  ) {
    $this->factoryDiscovery = $factoryDiscovery;
    $this->factoryToAdapter = $factoryToAdapter;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function classFilesIAGetPartials(ClassFilesIAInterface $classFilesIA) {

    $factoryVisitor = new FactoryVisitor_CollectAdapters(
      $this->factoryToAdapter);

    $this->factoryDiscovery->classFilesVisitFactories(
      $classFilesIA,
      $factoryVisitor);

    return $factoryVisitor->getPartials();
  }
}
