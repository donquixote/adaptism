<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_SmartChain implements SpecificAdapterInterface {

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][][]
   *   Format: $[$sourceType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[][]
   *   Format: $[$sourceType] = $partials
   */
  private $partialsBySourceType = [];

  /**
   * @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[] $partials
   */
  public function __construct(array $partials) {

    $indices = [];
    $specifities = [];
    $i = 0;
    foreach ($partials as $partial) {
      ++$i;
      $indices[] = $i;
      $specifities[] = $partial->getSpecifity();
    }

    array_multisort($specifities, SORT_DESC, $indices, $partials);

    $this->partials = $partials;
  }

  /**
   * @return int
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * @param mixed $adaptee
   * @param string $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  public function adapt(
    $adaptee,
    $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    $partials = $this->sourceTypeAndTargetTypeGetPartials(
      \get_class($adaptee),
      $interface);

    foreach ($partials as $partial) {

      $candidate = $partial->adapt($adaptee, $interface, $universalAdapter);

      if ($candidate instanceof $interface) {
        return $candidate;
      }
    }

    return NULL;
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function sourceTypeAndTargetTypeGetPartials($sourceType, $targetType): array {

    return $this->partialsGrouped[$sourceType][$targetType]
      ?? ($this->partialsGrouped[$sourceType][$targetType] = $this->sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType));
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType): array {

    return array_intersect_key(
      $this->sourceTypeGetPartials($sourceType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function targetTypeGetPartials($interface): array {

    return $this->partialsByTargetType[$interface]
      ?? $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function targetTypeCollectPartials($targetType): array {

    $partials = [];
    /** @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->providesResultType($targetType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function sourceTypeGetPartials($interface): array {

    return $this->partialsBySourceType[$interface]
      ?? $this->partialsBySourceType[$interface] = $this->sourceTypeCollectPartials($interface);
  }

  /**
   * @param string $sourceType
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  private function sourceTypeCollectPartials($sourceType): array {

    $partials = [];
    /** @var \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return null;
  }

  /**
   * @param string $destinationInterface
   *
   * @return bool
   */
  public function providesResultType($destinationInterface): bool {
    return [] !== $this->targetTypeGetPartials($destinationInterface);
  }

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool {
    return [] !== $this->sourceTypeGetPartials($sourceClass);
  }

}
