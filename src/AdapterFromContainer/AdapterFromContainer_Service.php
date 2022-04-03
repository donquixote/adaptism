<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_Service implements AdapterFromContainerInterface {

  public function __construct(
    private string $serviceId,
  ) {}

  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $candidate = $container->get($this->serviceId);
    if (!$candidate instanceof SpecificAdapterInterface) {
      throw new \Exception(\sprintf(
        'Expected %s, found %s, for service %s',
        SpecificAdapterInterface::class,
        \get_debug_type($candidate),
        $this->serviceId,
      ));
    }
  }

}
