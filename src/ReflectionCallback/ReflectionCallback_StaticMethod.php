<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ReflectionCallback;

class ReflectionCallback_StaticMethod implements ReflectionCallbackInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   * @param \ReflectionMethod $reflectionMethod
   */
  public function __construct(
    private string $class,
    private \ReflectionMethod $reflectionMethod,
  ) {
    if (!\is_a($class, $reflectionMethod->getDeclaringClass()->getName(), true)) {
      throw new \InvalidArgumentException(\sprintf(
        'Expected a (sub)class of %s, found %s.',
        $this->reflectionMethod->getDeclaringClass(),
        $class,
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
    return [$this->class, $this->reflectionMethod->getName()];
  }

}
