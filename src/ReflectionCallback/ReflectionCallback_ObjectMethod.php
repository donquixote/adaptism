<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ReflectionCallback;

class ReflectionCallback_ObjectMethod implements ReflectionCallbackInterface {

  public function __construct(
    object $object,
    private \ReflectionMethod $reflectionMethod,
  ) {
    if (!$object instanceof ($reflectionMethod->getDeclaringClass())) {
      throw new \InvalidArgumentException(\sprintf(
        'Expected an instance of %s, found an instance of %s.',
        $this->reflectionMethod->getDeclaringClass(),
        get_class($object),
      ));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributes(?string $name = null, int $flags = 0): array {
    return $this->reflectionMethod->getAttributes($name, $flags);
  }

  /**
   * {@inheritdoc}
   */
  public function getParameters(): array {
    return $this->reflectionMethod->getParameters();
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnType(): \ReflectionType {
    return $this->reflectionMethod->getReturnType();
  }

  /**
   * {@inheritdoc}
   */
  public function getCallable(): callable {
    return [$this->reflectionClass->getName(), $this->reflectionMethod->getName()];
  }

}
