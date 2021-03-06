<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter;

interface UniversalAdapterInterface {

  /**
   * @template T as object
   *
   * @param object $adaptee
   * @param class-string<T> $resultType
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $universalAdapter
   *   Top-level universal adapter, or NULL to use the object itself.
   *
   * @return T|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object;

}
