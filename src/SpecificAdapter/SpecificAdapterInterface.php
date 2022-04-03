<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

interface SpecificAdapterInterface {

  public const METHOD_NAME = [self::class, 'adapt'][1];

  /**
   * @template T as object
   *
   * @param object $adaptee
   * @param class-string<T> $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL if not found.
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  public function adapt(
    object $adaptee,
    string $interface,
    UniversalAdapterInterface $universalAdapter,
  ): ?object;

}
