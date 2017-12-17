<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Partial;

use Donquixote\Adaptism\ATA\ATAInterface;

abstract class ATAPartialBase implements ATAPartialInterface {

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
  public function withSourceType($sourceType) {

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
  public function withResultType($resultType) {

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
   * @return $this|\Donquixote\Adaptism\ATA\Partial\ATAPartialBase
   */
  public function withTypes($sourceType, $resultType) {

    if ($sourceType === $this->sourceType && $resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->sourceType = $sourceType;
    $clone->resultType = $resultType;
    return $clone;
  }

  /**
   * @param object $original
   * @param string $interface
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return null|object
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  final public function adapt(
    $original,
    $interface,
    ATAInterface $ata
  ) {

    if (NULL !== $this->sourceType && !$original instanceof $this->sourceType) {
      return NULL;
    }

    $candidate = $this->doAdapt($original, $interface, $ata);

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
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   *
   * @return mixed
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  abstract protected function doAdapt(
    $original,
    $interface,
    ATAInterface $ata);

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass) {
    return NULL === $this->sourceType
      || is_a($sourceClass, $this->sourceType, TRUE);
  }

  public function getResultType(): ?string {
    return $this->resultType;
  }

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface) {
    return NULL === $this->resultType
      || is_a($this->resultType, $resultInterface, TRUE);
  }

}
