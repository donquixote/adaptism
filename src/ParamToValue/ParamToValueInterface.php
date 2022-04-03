<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\ParamToValue;

interface ParamToValueInterface {

  public function paramGetValue(\ReflectionParameter $parameter): mixed;

}
