<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color\Hex;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class HexColor implements HexColorInterface {

  /**
   * @param string $hexCode
   */
  public function __construct(
    private string $hexCode,
  ) {}

  #[Adapter(-2)]
  public static function bridge(
    #[Adaptee] object $adaptee,
    #[AdapterTargetType] string $targetType,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?object {
    static $recursion = 0;
    if ($recursion > 1) {
      return null;
    }
    ++$recursion;
    try {
      $bridge = $universalAdapter->adapt(
        $adaptee,
        HexColorInterface::class);
      if ($bridge === null) {
        return null;
      }
      return $universalAdapter->adapt(
        $bridge,
        $targetType);
    }
    finally {
      --$recursion;
    }
  }

  /**
   * @param \Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface $rgbColor
   *
   * @return self
   */
  #[Adapter]
  public static function fromRgb(
    #[Adaptee] RgbColorInterface $rgbColor,
  ): self {
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
