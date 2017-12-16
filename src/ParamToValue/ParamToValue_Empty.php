<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ParamToValue;

class ParamToValue_Empty implements ParamToValueInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param) {
    return false;
  }

  /**
   * @param \ReflectionParameter $param
   * @param mixed|null $else
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param, $else = NULL) {
    return $else;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  public function paramGetPhp(\ReflectionParameter $param) {
    // Not supported here.
    return NULL;
  }

}
