<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_Callback implements AdapterFromContainerInterface {

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<string> $serviceIds
   *
   * @noinspection PhpDocSignatureInspection
   */
  public function __construct(
    private array|object|string $callback,
    private bool $hasResultTypeParameter,
    private bool $hasUniversalAdapterParameter,
    private array $serviceIds,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $args = [];
    foreach ($this->serviceIds as $id) {
      $args[] = $container->get($id);
    }
    return new SpecificAdapter_Callback(
      $this->callback,
      $this->hasResultTypeParameter,
      $this->hasUniversalAdapterParameter,
      $args,
    );
  }

}
