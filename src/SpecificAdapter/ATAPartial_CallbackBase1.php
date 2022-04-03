<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;

abstract class ATAPartial_CallbackBase1 extends SpecificAdapterBase {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface
   */
  private $argsMap;

  /**
   * @param \Donquixote\Adaptism\UniversalAdapter\ArgsMap\ArgsMapInterface $argsMap
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  public function __construct(
    ArgsMapInterface $argsMap,
    $sourceType = NULL,
    $resultType = NULL
  ) {
    $this->argsMap = $argsMap;
    parent::__construct($sourceType, $resultType);
  }

  /**
   * @param object $original
   * @param string $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $ata
   *
   * @return null|object
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_MisbehavingATA
   */
  final protected function doAdapt(
    $original,
    $interface,
    UniversalAdapterInterface $ata
  ) {
    $args = $this->argsMap->buildArgs($original, $ata);

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
