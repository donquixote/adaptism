<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

class AdapterDefinitionList_Discovery implements AdapterDefinitionListInterface {

  public function __construct(
    private ReflectionClassesIAInterface $reflectionClassesIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    $definitions = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      $adapterAttribute = AttributesUtil::getSingle($reflectionClass, Adapter::class);
      if ($adapterAttribute !== null) {
        $definitions[$reflectionClass->getName()] = $adapterAttribute->onClass($reflectionClass);
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        $adapterAttribute = AttributesUtil::getSingle($reflectionMethod, Adapter::class);
        if ($adapterAttribute) {
          $definitions[$reflectionClass->getName() . '::' . $reflectionMethod->getName()] = $adapterAttribute->onMethod($reflectionClass, $reflectionMethod);
        }
      }
    }
    return $definitions;
  }

}
