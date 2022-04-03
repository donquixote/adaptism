<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FactoryToAdapter;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Annotation\Reader\AnnotationReader;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

class FactoryToAdapter_CheckAnnotation implements FactoryToAdapterInterface {

  /**
   * @var \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Adaptism\Discovery\FactoryToAdapter\FactoryToAdapterInterface $decorated
   */
  public function __construct(FactoryToAdapterInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SpecificAdapterInterface {

    if (!$this->factoryIsAdapter($factory)) {
      return null;
    }

    return $this->decorated->factoryGetPartial($factory);
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return bool
   */
  private function factoryIsAdapter(ReflectionFactoryInterface $factory): bool {

    $reader = AnnotationReader::createWithInstantiator();

    foreach ($reader->reflectorGetAnnotations($factory->getReflector()) as $annotation) {
      if ($annotation instanceof Adapter) {
        return true;
      }
    }

    return false;
  }
}
