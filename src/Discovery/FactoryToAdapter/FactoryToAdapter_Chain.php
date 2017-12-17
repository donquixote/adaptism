<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

class FactoryToAdapter_Chain implements FactoryToAdapterInterface {

  /**
   * @var \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface[]
   */
  private $chained;

  /**
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface[] $chained
   */
  public function __construct(array $chained) {
    $this->chained = $chained;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?ATAPartialInterface {

    foreach ($this->chained as $fta) {
      if (null !== $partial = $fta->factoryGetPartial($factory)) {
        return $partial;
      }
    }

    return null;
  }
}
