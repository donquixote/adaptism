<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Rgb;

interface RgbColorInterface {

  /**
   * @return int
   */
  public function red();

  /**
   * @return int
   */
  public function green();

  /**
   * @return int
   */
  public function blue();

}
