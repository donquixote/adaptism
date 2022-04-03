<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_Callback;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use Donquixote\Adaptism\Util\NewInstance;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase {

  public function testDefinitionList(): void {

    $expected = [];

    $expected[Countable_Traversable::class] = new AdapterDefinition_Simple(
      \Traversable::class,
      Countable_Traversable::class,
      0,
      new AdapterFromContainer_Callback(
        [NewInstance::class, Countable_Traversable::class],
        false,
        false,
        [],
      ),
    );

    $expected[HexColor::class . '::fromRgb'] = new AdapterDefinition_Simple(
      RgbColorInterface::class,
      HexColor::class,
      1,
      new AdapterFromContainer_Callback(
        [HexColor::class, 'fromRgb'],
        false,
        false,
        [],
      ),
    );

    self::assertSameExportAndSort(
      $expected,
      FixturesUtil::getDefinitionList()->getDefinitions());
  }

  /**
   * @param array $expected
   * @param array $actual
   */
  private static function assertSameExportAndSort(array $expected, array $actual): void {
    self::assertSame(
      self::exportItemsAndSort($expected),
      self::exportItemsAndSort($actual));
  }

  /**
   * @param mixed[] $items
   *
   * @return string
   */
  private static function exportItemsAndSort(array $items): string {

    $export = [];
    foreach ($items as $item) {
      $export[] = var_export($item, true);
    }

    array_multisort($export, $items);

    return var_export($items, true);
  }

}
