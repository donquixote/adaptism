<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToAdapterInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SpecificAdapterInterface;
}
