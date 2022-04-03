<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Callback;

class Callback_ObjectMethod implements CallbackInterface {

  public function __construct(
    private string $class,
    private string $method,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function __invoke(...$args): mixed {
    $object = \array_shift($args);
    if (!$object instanceof $this->class) {
      throw new \InvalidArgumentException(\sprintf(
        'Expected a %s object, found %s.',
        $this->class,
        \is_object($object)
          ? 'a ' . \get_class($object) . ' object'
          : 'a ' . \gettype($object) . ' value',
      ));
    }
    return $object->{$this->method}(...$args);
  }

}
