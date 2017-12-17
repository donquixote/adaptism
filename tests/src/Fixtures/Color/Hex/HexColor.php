<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Hex;

use Donquixote\Adaptism\Annotation\Adapter;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;

class HexColor implements HexColorInterface {

  /**
   * @var string
   */
  private $hexCode;

  /**
   * @Adapter
   *
   * @param \Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface $rgbColor
   *
   * @return self
   */
  public static function fromRgb(RgbColorInterface $rgbColor): self {
    return new self(
      sprintf(
        '%02x%02x%02x',
        $rgbColor->red(), $rgbColor->green(), $rgbColor->blue()));
  }

  /**
   * @param string $hexCode
   */
  public function __construct($hexCode) {
    $this->hexCode = $hexCode;
  }

  /**
   * @return string
   *   The 6-char hex representation. Without any leading "#".
   */
  public function getHexCode(): string {
    return $this->hexCode;
  }
}
