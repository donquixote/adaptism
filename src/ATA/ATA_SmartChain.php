<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA;

use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;

class ATA_SmartChain implements ATAInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][][]
   *   Format: $[$sourceType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][]
   *   Format: $[$sourceType] = $partials
   */
  private $partialsBySourceType = [];

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[] $partials
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
   * @param object $original
   * @param string $destinationInterface
   *
   * @return object|null
   *   An instance of $destinationInterface.
   */
  public function adapt(object $original, string $destinationInterface): ?object {

    if ($original instanceof $destinationInterface) {
      return $original;
    }

    $partials = $this->sourceTypeAndTargetTypeGetPartials(
      \get_class($original),
      $destinationInterface);

    if ([] === $partials) {
      // No partials available for given types.
      return null;
    }

    foreach ($partials as $partial) {

      try {
        $candidate = $partial->adapt($original, $destinationInterface, $this);
      }
      catch (Exception_MisbehavingATA $e) {
        // @todo Log misbehaving partial.
        unset($e);
        continue;
      }

      if (NULL !== $candidate) {
        if ($candidate instanceof $destinationInterface) {
          return $candidate;
        }
        // @todo Log misbehaving partial.
      }
    }

    // Partials returned nothing.
    return NULL;
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeAndTargetTypeGetPartials($sourceType, $targetType): array {

    return $this->partialsGrouped[$sourceType][$targetType] ?? ($this->partialsGrouped[$sourceType][$targetType] = $this->sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType));
  }

  /**
   * @param string $sourceType
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeAndTargetTypeCollectPartials($sourceType, $targetType): array {

    return array_intersect_key(
      $this->sourceTypeGetPartials($sourceType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function targetTypeGetPartials($interface): array {

    return $this->partialsByTargetType[$interface]
      ?? $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function targetTypeCollectPartials($targetType): array {

    $partials = [];
    /** @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface $partial */
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
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeGetPartials($interface): array {

    return $this->partialsBySourceType[$interface]
      ?? $this->partialsBySourceType[$interface] = $this->sourceTypeCollectPartials($interface);
  }

  /**
   * @param string $sourceType
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function sourceTypeCollectPartials($sourceType): array {

    $partials = [];
    /** @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface): bool {
    return [] !== $this->targetTypeGetPartials($interface);
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSourceClass($interface): bool {
    return [] !== $this->sourceTypeGetPartials($interface);
  }
}
