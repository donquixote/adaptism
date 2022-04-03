<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_Class;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;
use Psr\Container\ContainerInterface;

class AdapterMap_Discovery implements AdapterMapInterface {

  public function __construct(
    private ReflectionClassesIAInterface $reflectionClassesIA,
    private ContainerInterface $container,
  ) {}

  public function getSuitableAdapters(?string $source_type, ?string $target_type): array {
    $partials = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      foreach ($reflectionClass->getAttributes(Adapter::class, \ReflectionAttribute::IS_INSTANCEOF) as $reflectionAttribute) {
        /**
         * @var \Donquixote\Adaptism\Attribute\Adapter $instance
         * @psalm-ignore-var
         */
        $instance = $reflectionAttribute->newInstance();
        $constructor = $reflectionClass->getConstructor();
        if ($constructor === null) {
          throw new \Exception(\sprintf(
            'Expected a constructor on %s.',
            $reflectionClass->getName(),
          ));
        }
        if ($constructor->getParameters() === []) {
          throw new \Exception(\sprintf(
            'Expected at least one parameter in %s.',
            $reflectionClass->getName() . '::__construct()',
          ));
        }
        if (!$reflectionClass->isSubclassOf(IncarnatorPartialInterface::class)) {
          return IncarnatorPartial_Class::fromClass($reflectionClass, $paramToValue);
        }

      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        foreach ($reflectionMethod->getAttributes(
          Adapter::class,
          \ReflectionAttribute::IS_INSTANCEOF
        ) as $attribute) {
          /**
           * @var \Donquixote\Ock\Attribute\Incarnator\OckIncarnator $instance
           * @psalm-ignore-var
           */
          $instance = $attribute->newInstance();
          $partials[] = $instance->methodGetPartial(
            $reflectionMethod,
            $this->paramToValue);
        }
      }
    }
    return $partials;
  }

}
