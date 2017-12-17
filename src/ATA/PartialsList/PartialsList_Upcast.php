<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_Upcast implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  private $decorated;

  /**
   * @var null|string[][]
   *   Format: $[$parentClassOrInterface][] = $childClassOrInterface
   */
  private $hierarchyMap;

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface $decorated
   */
  public function __construct(PartialsListInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[]
   */
  public function getTypes(): array {
    $types = array_keys($this->getHierarchyMap());
    return array_combine($types, $types);
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    $hierarchyMap = $this->getHierarchyMap();

    $partials = [];
    foreach ($hierarchyMap[$classOrInterface] ?? [] as $childClass) {
      foreach ($this->decorated->typeGetPartials($childClass) as $partial) {
        $partials[] = $partial;
      }
    }

    // Partials with return type 'object' participate in everything.
    foreach ($this->decorated->typeGetPartials('object') as $partial) {
      if ($partial->providesResultType($classOrInterface)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @return string[][]
   *   Format: $[$parentClassOrInterface][] = $childClassOrInterface
   */
  private function getHierarchyMap(): array {
    return $this->hierarchyMap
      ?? $this->hierarchyMap = $this->buildHierarchyMap();
  }

  /**
   * @return string[][]
   *   Format: $[$parentClassOrInterface][] = $childClassOrInterface
   */
  private function buildHierarchyMap(): array {

    $map = [];
    foreach ($this->decorated->getTypes() as $class) {

      $map[$class][] = $class;

      if (!class_exists($class) && !interface_exists($class)) {
        continue;
      }

      $reflClass = new \ReflectionClass($class);

      foreach ($reflClass->getInterfaceNames() as $interface) {
        $map[$interface][] = $class;
      }

      $reflParent = $reflClass;
      while ($reflParent = $reflParent->getParentClass()) {
        $map[$reflParent->getName()][] = $class;
      }
    }

    return $map;
  }
}
