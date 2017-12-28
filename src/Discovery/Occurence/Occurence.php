<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\Occurence;

class Occurence {

  /**
   * @var array
   */
  private $definition;

  /**
   * @var string|null
   */
  private $returnTypeClassName;

  /**
   * @param string $class
   * @param string $typePrefix
   *
   * @return self
   */
  public static function fromClassName(string $class, string $typePrefix): self {
    $occurence = new self(
      [
        'type' => $typePrefix . 'Class',
        'class' => $class,
      ]);
    $occurence->returnTypeClassName = $class;
    return $occurence;
  }

  /**
   * @param array $definition
   */
  public function __construct(array $definition) {
    $this->definition = $definition;
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param string $typePrefix
   *
   * @return self
   */
  public static function fromStaticMethod(string $class, string $methodName, string $typePrefix): self {
    return new self(
      [
        'type' => $typePrefix . 'StaticFactory',
        'class' => $class,
        'method' => $methodName,
      ]);
  }

  /**
   * @param string $class
   *
   * @return static
   */
  public function withReturnTypeClassName($class): self {
    $clone = clone $this;
    $clone->returnTypeClassName = $class;
    return $clone;
  }

  /**
   * @return null|string
   */
  public function getReturnTypeClassName(): ?string {
    return $this->returnTypeClassName;
  }

  /**
   * @return array
   */
  public function getDefinition(): array {
    return $this->definition;
  }

}
