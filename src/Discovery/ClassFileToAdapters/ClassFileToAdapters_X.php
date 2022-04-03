<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\ClassFileToAdapters;

use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapter;
use Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactories;
use Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface;
use Donquixote\FactoryReflection\ClassToFactories\ClassToFactories;
use Donquixote\ReflectionKit\AliasResolver\AliasResolver;
use Donquixote\ReflectionKit\ContextFinder\ContextFinder_PhpTokenParser;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class ClassFileToAdapters_X implements ClassFileToAdaptersInterface {

  /**
   * @var ClassFileToFactoriesInterface
   */
  private $classFileFactories;

  /**
   * @var \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface
   */
  private $factoryToAdapterPartial;

  /**
   * @var \Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface
   */
  private $contextFinder;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    return new self(
      new ClassFileToFactories(
        ClassToFactories::create(),
        new ContextFinder_PhpTokenParser()),
      FactoryToAdapter::create($paramToValue));
  }

  /**
   * @param \Donquixote\FactoryReflection\ClassFileToFactories\ClassFileToFactoriesInterface $classFileFactories
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface $factoryToAdapterPartial
   */
  public function __construct(
    ClassFileToFactoriesInterface $classFileFactories,
    FactoryToAdapterInterface $factoryToAdapterPartial
  ) {
    $this->classFileFactories = $classFileFactories;
    $this->factoryToAdapterPartial = $factoryToAdapterPartial;
  }

  /**
   * @param string $class
   * @param string $fileRealpath
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function classFileGetPartials(string $class, string $fileRealpath): array {

    if (!class_exists($class)) {
      return [];
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    $reflClass = new \ReflectionClass($class);

    if ($fileRealpath !== $reflClass->getFileName()) {
      // Class is not defined here.
      return [];
    }

    $context = $this->contextFinder->reflectorGetContext($reflClass);

    AliasResolver::fromContext($context);

    $factories = $this->classFileFactories->classFileGetFactories(
      $class,
      $fileRealpath);

    $partials = [];
    foreach ($factories as $factory) {
      if (null !== $partial = $this->factoryToAdapterPartial->factoryGetPartial($factory)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }

}
