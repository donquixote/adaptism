<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_Callback;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_ObjectMethod;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\NewInstance;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;

/**
 * Marks a class or method as an adapter.
 *
 * If placed on a class, the first parameter of the constructor is considered
 * the adaptee object, and the class instance is considered the adapter.
 *
 * If placed on a method, the first parameter of that method is considered the
 * adaptee, and the return value is considered the adapter.
 *
 * If the method is not static, then an instance will be constructed based on
 * annotated constructor parameters.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class Adapter {

  public function __construct(
    private ?int $specifity = null
  ) {}

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \ReflectionException
   */
  public function onClass(\ReflectionClass $reflectionClass): AdapterDefinitionInterface {
    $class = $reflectionClass->getName();
    $constructor = $reflectionClass->getConstructor();
    if ($constructor === null) {
      throw new \ReflectionException(\sprintf(
        'Expected a constructor on %s.',
        $reflectionClass->getName(),
      ));
    }
    $parameters = $constructor->getParameters();
    $sourceType = $this->extractSourceType(
      $parameters,
      $specifity,
      $class . '::__construct()',
    );
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    $factory = $this->createFactory(
      [NewInstance::class, $class],
      false,
      $hasUniversalAdapterParameter,
      $parameters,
    );
    return new AdapterDefinition_Simple(
      $sourceType,
      $class,
      $this->specifity ?? $specifity,
      $factory,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   * @throws \ReflectionException
   */
  public function onMethod(
    \ReflectionClass $reflectionClass,
    \ReflectionMethod $reflectionMethod
  ): AdapterDefinitionInterface {
    $class = $reflectionClass->getName();
    $method = $reflectionMethod->getName();
    $where = $class . '::' . $method . '()';
    $parameters = $reflectionMethod->getParameters();
    $sourceType = $this->extractSourceType($parameters, $specifity, $where);
    $hasResultTypeParameter = $this->extractHasResultTypeParameter($parameters);
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    if ($reflectionMethod->isStatic()) {
      $factory = $this->createFactory(
        [$class, $method],
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $parameters,
      );
    }
    else {
      if ($parameters !== []) {
        throw new \ReflectionException(\sprintf(
          'Leftover parameters %s on %s.',
          \implode(', ', \array_map(
            static function (\ReflectionParameter $parameter) {
              return '$' . $parameter->getName();
            },
            $parameters,
          )),
          $where,
        ));
      }
      $constructorServiceIds = \array_map(
        [$this, 'extractServiceId'],
        $reflectionClass->getConstructor()?->getParameters() ?? [],
      );
      $factory = new AdapterFromContainer_ObjectMethod(
        [NewInstance::class, $class],
        $method,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $constructorServiceIds,
      );
    }
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflectionMethod);
    return new AdapterDefinition_Simple(
      $sourceType,
      $returnClass,
      $this->specifity ?? \count($reflectionClass->getInterfaceNames()),
      $factory,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   * @param int|null $specifity
   * @param string $where
   *
   * @return string
   * @throws \ReflectionException
   */
  private function extractSourceType(array &$parameters, ?int &$specifity, string $where): string {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      throw new \ReflectionException(\sprintf(
        'Expected at least one parameter in %s.',
        $where,
      ));
    }
    AttributesUtil::requireHasSingle($parameter, Adaptee::class);
    $type = ReflectionTypeUtil::requireGetClassLikeType($parameter);
    $reflectionClass = new \ReflectionClass($type);
    $specifity = \count($reflectionClass->getInterfaceNames());
    return $type;
  }

  /**
   * @param array $parameters
   *
   * @return bool
   * @throws \ReflectionException
   */
  private function extractHasResultTypeParameter(array &$parameters): bool {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      return false;
    }
    if (!AttributesUtil::hasSingle($parameter, AdapterTargetType::class)) {
      \array_unshift($parameters, $parameter);
      return false;
    }
    ReflectionTypeUtil::requireBuiltinType($parameter, 'string');
    return true;
  }

  /**
   * @param array $parameters
   *
   * @return bool
   * @throws \ReflectionException
   */
  private function extractHasUniversalAdapterParameter(array &$parameters): bool {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      return false;
    }
    if (!AttributesUtil::hasSingle($parameter, UniversalAdapter::class)) {
      \array_unshift($parameters, $parameter);
      return false;
    }
    ReflectionTypeUtil::requireClassLikeType($parameter, UniversalAdapterInterface::class);
    return true;
  }

  /**
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param array $parameters
   *
   * @return \Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface
   */
  private function createFactory(
    callable $callback,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $parameters,
  ): AdapterFromContainerInterface {
    $serviceIds = $parameters
      ? \array_map([$this, 'extractServiceId'], $parameters)
      : [];
    return new AdapterFromContainer_Callback(
      $callback,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $serviceIds,
    );
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   * @throws \ReflectionException
   */
  private function extractServiceId(\ReflectionParameter $parameter): string {
    return AttributesUtil::requireGetSingle($parameter, GetService::class)->getId()
      ?? ReflectionTypeUtil::requireGetClassLikeType($parameter);
  }

}
