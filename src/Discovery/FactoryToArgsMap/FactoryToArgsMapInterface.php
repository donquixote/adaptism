<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToArgsMap;

use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToArgsMapInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface
   */
  public function factoryGetArgsMap(ReflectionFactoryInterface $factory): ArgsMapInterface;
}
