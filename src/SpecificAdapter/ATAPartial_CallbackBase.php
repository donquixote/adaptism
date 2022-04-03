<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;

abstract class ATAPartial_CallbackBase extends SpecificAdapterBase {

  /**
   * @var mixed[]
   */
  private $defaultArgs = [];

  /**
   * @var int|null
   */
  private $ataPos = 1;

  /**
   * @param mixed[] $defaultArgs
   *   Format: [null, $arg1, $arg2, $arg3, ..]
   *
   * @return static
   */
  public function withDefaultArgs(array $defaultArgs) {
    $clone = clone $this;
    $clone->defaultArgs = $defaultArgs;
    return $clone;
  }

  /**
   * @param int $pos
   *
   * @return static
   */
  public function withAtaArgPos($pos = 1) {
    $clone = clone $this;
    $clone->ataPos = $pos;
    return $clone;
  }

  /**
   * @param $original
   * @param $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $helper
   *
   * @return null|object
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  protected function doAdapt(
    $original,
    $interface,
    UniversalAdapterInterface $helper
  ) {
    $args = [$original];
    if (NULL !== $this->ataPos) {
      $args[$this->ataPos] = $helper;
    }
    $args += $this->defaultArgs;

    // Other arguments, e.g. services, might already be part of the callback.
    try {
      return $this->invokeArgs($args);
    }
    catch (\Exception $e) {
      throw new Exception_MisbehavingATA("Exception in callback.", 0, $e);
    }
  }

  /**
   * @param mixed[] $args
   *
   * @return null|object
   *
   * @throws \Exception
   */
  abstract protected function invokeArgs(array $args);
}
