<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Hex;

use Donquixote\Adaptism\Attribute\Adaptee;
use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;

class HexColor implements HexColorInterface {

  /**
   * @param string $hexCode
   */
  public function __construct(
    private string $hexCode,
  ) {}

  /**
   * @param \Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface $rgbColor
   *
   * @return self
   */
  #[Adapter]
  public static function fromRgb(#[Adaptee] RgbColorInterface $rgbColor): self {
    return new self(
      sprintf(
        '%02x%02x%02x',
        $rgbColor->red(), $rgbColor->green(), $rgbColor->blue()));
  }

  /**
   * @return string
   *   The 6-char hex representation. Without any leading "#".
   */
  public function getHexCode(): string {
    return $this->hexCode;
  }
}
