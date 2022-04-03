<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\AdapterMap\AdapterMapInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_DispatchByType implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\AdapterMap\AdapterMapInterface $adapterMap
   */
  public function __construct(
    private AdapterMapInterface $adapterMap,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {
    $adapters = $this->adapterMap->getSuitableAdapters(
      \get_class($adaptee),
      $interface);

    foreach ($adapters as $adapter) {
      $candidate = $adapter->adapt($adaptee, $interface, $universalAdapter);
      if ($candidate instanceof $interface) {
        return $candidate;
      }
      if ($candidate !== null) {
        throw new Exception_MisbehavingATA(\sprintf(
          'Expected %s object, found %s.',
          $interface,
          \is_object($candidate)
            ? \get_class($candidate) . ' object'
            : \gettype($candidate) . ' value'
        ));
      }
    }

    // No successful adapter.
    return NULL;
  }

}
