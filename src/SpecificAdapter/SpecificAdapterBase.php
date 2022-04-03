<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

abstract class SpecificAdapterBase implements SpecificAdapterInterface {

  use ATAPartial_SpecifityTrait;

  /**
   * @var null|string
   */
  private $sourceType;

  /**
   * @var null|string
   */
  private $resultType;

  /**
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  protected function __construct($sourceType = NULL, $resultType = NULL) {
    $this->sourceType = $sourceType;
    $this->resultType = $resultType;
  }

  /**
   * @param string $sourceType
   *
   * @return static
   */
  public function withSourceType($sourceType): self {

    if ($sourceType === $this->sourceType) {
      return $this;
    }

    $clone = clone $this;
    $clone->sourceType = $sourceType;
    return $clone;
  }

  /**
   * @param string $resultType
   *
   * @return static
   */
  public function withResultType($resultType): self {

    if ($resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->resultType = $resultType;
    return $clone;
  }

  /**
   * @param string $sourceType
   * @param string $resultType
   *
   * @return static
   */
  public function withTypes($sourceType, $resultType): self {

    if ($sourceType === $this->sourceType && $resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->sourceType = $sourceType;
    $clone->resultType = $resultType;
    return $clone;
  }

  /**
   * @param object $adaptee
   * @param string $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   */
  final public function adapt(
    $adaptee,
    $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    if (NULL !== $this->sourceType && !$adaptee instanceof $this->sourceType) {
      return NULL;
    }

    $candidate = $this->doAdapt($adaptee, $interface, $universalAdapter);

    if (NULL === $candidate) {
      return NULL;
    }

    if (!$candidate instanceof $interface) {
      # kdpm($candidate, "Expected $interface, found sth else.");
      # kdpm($this, '$this');
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param object $original
   * @param string $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return null|object
   *
   */
  abstract protected function doAdapt(
    $original,
    $interface,
    UniversalAdapterInterface $ata);

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass): bool {
    return NULL === $this->sourceType
      || is_a($sourceClass, $this->sourceType, TRUE);
  }

  /**
   * @return null|string
   */
  public function getResultType(): ?string {
    return $this->resultType;
  }

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface): bool {
    return NULL === $this->resultType
      || is_a($this->resultType, $resultInterface, TRUE);
  }

}
