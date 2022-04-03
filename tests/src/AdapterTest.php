<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase {
  
  public function test(): void {
    $adapter = FixturesUtil::getUniversalAdapter();

    $traversable = (static function () {yield 'a'; yield 'b';})();
    $countable = $adapter->adapt($traversable, \Countable::class);
    self::assertInstanceOf(\Countable::class, $countable);
    self::assertSame(2, $countable->count());

    $rgb = new RgbColor(255, 50, 80);
    $hex = $adapter->adapt($rgb, HexColorInterface::class);
    self::assertInstanceOf(HexColorInterface::class, $hex);
    self::assertSame('ff3250', $hex->getHexCode());
  }

}
