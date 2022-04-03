<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Psr\Container\ContainerInterface;

class AdapterMap_DefinitionList implements AdapterMapInterface {

  private ?AdapterMapInterface $proxy;

  public function __construct(
    private AdapterDefinitionListInterface $definitionList,
    private ContainerInterface $container,
  ) {}

  public function getSuitableAdapters(?string $source_type, ?string $result_type): array {
    // @todo Cache the proxy.
    $proxy = $this->proxy
      ??= new AdapterMap_FixedSerializable(
      $this->definitionList->getDefinitions(),
      $this->container);
    return $proxy->getSuitableAdapters($source_type, $result_type);
  }

}
