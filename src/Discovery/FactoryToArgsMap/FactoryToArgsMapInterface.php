<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToArgsMap;

use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToArgsMapInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface
   */
  public function factoryGetArgsMap(ReflectionFactoryInterface $factory);
}
