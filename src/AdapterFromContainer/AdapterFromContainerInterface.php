<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerInterface;

interface AdapterFromContainerInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface;

}
