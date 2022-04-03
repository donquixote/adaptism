<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ReflectionCallback;

use Donquixote\Adaptism\Util\NewInstance;

class ReflectionCallback_NewClassInstance implements ReflectionCallbackInterface {

  public function __construct(
    private \ReflectionClass $reflectionClass
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getAttributes(?string $name = null, int $flags = 0): array {
    return $this->reflectionClass->getAttributes($name, $flags);
  }

  /**
   * {@inheritdoc}
   */
  public function getParameters(): array {
    $constructor = $this->reflectionClass->getConstructor();
    return ($constructor !== NULL)
      ? $constructor->getParameters()
      : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getReturnType(): \ReflectionType {
    /** @noinspection PhpUnhandledExceptionInspection */
    return (new \ReflectionFunction(eval(\sprintf(
        'return static function (): %s {};',
        $this->reflectionClass->getName(),
    ))))->getReturnType();
  }

  /**
   * {@inheritdoc}
   */
  public function getCallable(): callable {
    return [NewInstance::class, $this->reflectionClass->getName()];
  }

}
