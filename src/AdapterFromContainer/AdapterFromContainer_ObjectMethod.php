<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_ObjectMethod implements AdapterFromContainerInterface {

  private array|string|object $factoryCallback;

  /**
   * Constructor.
   *
   * @param callable $factoryCallback
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<string> $factoryServiceIds
   */
  public function __construct(
    callable $factoryCallback,
    private string $method,
    private bool $hasResultTypeParameter,
    private bool $hasUniversalAdapterParameter,
    private array $factoryServiceIds,
  ) {
    $this->factoryCallback = $factoryCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $args = [];
    foreach ($this->factoryServiceIds as $id) {
      $args[] = $container->get($id);
    }
    $object = ($this->factoryCallback)(...$args);
    return new SpecificAdapter_Callback(
      [$object, $this->method],
      $this->hasResultTypeParameter,
      $this->hasUniversalAdapterParameter,
      [],
    );
  }

}
