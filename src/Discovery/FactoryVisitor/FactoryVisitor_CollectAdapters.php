<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryVisitor;

use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\Visitor\Factory\FactoryVisitorInterface;

class FactoryVisitor_CollectAdapters implements FactoryVisitorInterface {

  /**
   * @var \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface
   */
  private $factoryToAdapter;

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private $partials = [];

  /**
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface $factoryToAdapter
   */
  public function __construct(FactoryToAdapterInterface $factoryToAdapter) {
    $this->factoryToAdapter = $factoryToAdapter;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   */
  public function visitFactory(ReflectionFactoryInterface $factory): void {

    if (null === $partial = $this->factoryToAdapter->factoryGetPartial($factory)) {
      return;
    }

    $this->partials[] = $partial;
  }

  /**
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function getPartials(): array {
    return $this->partials;
  }
}
