<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures;

class GeneratorCollection {

  /**
   * @return \Iterator|string[]
   */
  public static function trafficLight(): \Iterator {
    yield 'red';
    yield 'yellow';
    yield 'green';
  }

}
