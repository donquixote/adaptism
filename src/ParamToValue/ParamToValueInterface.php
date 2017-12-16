<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ParamToValue;

/**
 * A service that can provide / guess default values for function parameters,
 * e.g. based on the type hint.
 */
interface ParamToValueInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param);

  /**
   * @param \ReflectionParameter $param
   * @param mixed|null $else
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param, $else = NULL);

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  public function paramGetPhp(\ReflectionParameter $param);

}
