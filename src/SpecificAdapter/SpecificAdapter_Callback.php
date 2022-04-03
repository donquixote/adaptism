<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_Callback implements SpecificAdapterInterface {

  private string|array|object $callback;

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<mixed> $moreArgs
   */
  public function __construct(
    callable $callback,
    private bool $hasResultTypeParameter,
    private bool $hasUniversalAdapterParameter,
    private array $moreArgs,
  ) {
    $this->callback = $callback;
  }

  public function adapt(
    object $adaptee,
    string $interface,
    UniversalAdapterInterface $universalAdapter,
  ): ?object {
    $args = [$adaptee];
    if ($this->hasResultTypeParameter) {
      $args[] = $interface;
    }
    if ($this->hasUniversalAdapterParameter) {
      $args[] = $universalAdapter;
    }
    return ($this->callback)(...$args, ...$this->moreArgs);
  }

}
