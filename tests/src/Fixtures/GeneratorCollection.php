<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

class GeneratorCollection {

  public static function trafficLight() {
    yield 'red';
    yield 'yellow';
    yield 'green';
  }

}
