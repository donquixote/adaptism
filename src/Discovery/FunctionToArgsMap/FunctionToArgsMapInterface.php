<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Discovery\FunctionToArgsMap;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface;

interface FunctionToArgsMapInterface {

  /**
   * @param \ReflectionFunctionAbstract $function
   *
   * @return \Donquixote\Adaptism\ATA\ArgsMap\ArgsMapInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function functionGetArgsMap(\ReflectionFunctionAbstract $function): ArgsMapInterface;
}
