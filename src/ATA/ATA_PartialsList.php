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
   * @param string $targetType
   *
   * @return object|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   */
  public function adapt($original, $targetType) {

    $sourceType = \get_class($original);

    $partials = $this->partialsGrouped[$targetType][$sourceType]
      ?? ($this->partialsGrouped[$targetType][$sourceType] = $this->typesGetPartials(
        $targetType,
        $sourceType));

    foreach ($partials as $partial) {

      try {
        $candidate = $partial->adapt($original, $targetType, $this);
      }
      catch (Exception_MisbehavingATA $e) {
        // @todo Log misbehaving partial.
        unset($e);
        continue;
      }

      if (NULL !== $candidate) {
        if ($candidate instanceof $targetType) {
          return $candidate;
        }
        // @todo Log misbehaving partial.
      }
    }

    return null;
  }

  private function typesGetPartials($targetType, $sourceType) {

    $partials = [];
    foreach ($this->partialsList->typeGetPartials($targetType) as $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
