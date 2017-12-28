<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA;

use Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;

class ATA_PartialsList implements ATAInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  private $partialsList;

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[][][]
   *   Format: $[$targetType][$sourceType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface $partialsList
   */
  public function __construct(PartialsListInterface $partialsList) {
    $this->partialsList = $partialsList;
  }

  /**
   * @param object $original
   * @param string $destinationInterface
   *
   * @return object|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   */
  public function adapt($original, string $destinationInterface) {

    $sourceType = \get_class($original);

    $partials = $this->partialsGrouped[$destinationInterface][$sourceType]
      ?? ($this->partialsGrouped[$destinationInterface][$sourceType] = $this->typesGetPartials(
        $destinationInterface,
        $sourceType));

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

    return null;
  }

  /**
   * @param string $targetType
   * @param string $sourceType
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function typesGetPartials(string $targetType, string $sourceType): array {

    $partials = [];
    foreach ($this->partialsList->typeGetPartials($targetType) as $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
