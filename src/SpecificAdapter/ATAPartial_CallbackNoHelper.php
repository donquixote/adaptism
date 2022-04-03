<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class ATAPartial_CallbackNoHelper extends SpecificAdapterBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string|null $sourceType
   *
   * @return self
   */
  public static function fromClassName($class, $sourceType = NULL): self {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self(
      $callback,
      $sourceType,
      $class);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $resultType
   *
   * @return self|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultType = NULL): ?self {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      $sourceType = NULL;
    }
    else {
      $sourceType = $t0->getName();
    }

    return new self($callback, $sourceType, $resultType);
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $sourceType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($sourceType, $resultType);
  }

  /**
   * @param $original
   * @param $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return null|object
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  protected function doAdapt(
    $original,
    $interface,
    UniversalAdapterInterface $ata
  ) {

    try {
      return $this->callback->invokeArgs([$original]);
    }
    catch (\Exception $e) {
      throw new Exception_MisbehavingATA("Exception in callback.", 0, $e);
    }
  }
}
