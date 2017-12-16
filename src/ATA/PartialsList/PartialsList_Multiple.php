<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\PartialsList;

class PartialsList_Multiple implements PartialsListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface[]
   */
  private $lists;

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface[] $lists
   *
   * @return \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface
   */
  public static function create(array $lists) {

    if ([] === $lists) {
      return new PartialsList_Empty();
    }

    if (1 === \count($lists)) {
      return reset($lists);
    }

    return new self($lists);
  }

  /**
   * @param \Donquixote\Adaptism\ATA\PartialsList\PartialsListInterface[] $decorated
   */
  public function __construct(array $decorated) {
    $this->lists = $decorated;
  }

  /**
   * @return string[]
   *   Format: $[$type] = $type
   */
  public function getTypes(): array {

    $types = [];
    foreach ($this->lists as $list) {
      $types += $list->getTypes();
    }

    return $types;
  }

  /**
   * @param string $classOrInterface
   *
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  public function typeGetPartials($classOrInterface): array {

    $partials = [];
    foreach ($this->lists as $list) {
      foreach ($list->typeGetPartials($classOrInterface) as $partial) {
        $partials[] = $partial;
      }
    }

    return $partials;
  }
}
