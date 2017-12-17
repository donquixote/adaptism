<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_Simple;
use Donquixote\Adaptism\ATA\ATABuilder;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_ClassInstance;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_Seed_Neutral_Object;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_StaticMethod;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase {

  public function testSimplePartialsList() {

    $partial = new ATAPartial_Seed_Neutral_Object((object)['x' => 'y']);

    $partialsList = ATABuilder::create()
      ->withCustomATA($partial)
      ->buildPartialsList();

    # self::assertSame('', \get_class($partialsList));

    self::assertSameExportAndSort(
      [
        \stdClass::class => \stdClass::class,
      ],
      $partialsList->getTypes());
  }

  public function testDefinitionList() {

    $list = FixturesUtil::getDefinitionList();

    self::assertSame(
      [
        HexColor::class => [
          [
            'type' => 'adapterStaticFactory',
            'class' => HexColor::class,
            'method' => 'fromRgb',
          ],
        ],
        Countable_Traversable::class => [
          [
            'type' => 'adapterClass',
            'class' => Countable_Traversable::class,
          ],
        ],
      ],
      $list->getDefinitionsByReturnType());
  }

  public function testPartialsList() {

    $list = FixturesUtil::getPartialsList();

    $expected = [];

    $expected[] = new ATAPartial_ClassInstance(
      new \ReflectionClass(Countable_Traversable::class),
      new ArgsMap_Simple(),
      \Traversable::class);

    self::assertSameExportAndSort(
      $expected,
      $list->typeGetPartials(\Countable::class));
  }

  public function testDiscoverPartials() {

    $expected = [];

    $expected[] = new ATAPartial_ClassInstance(
      new \ReflectionClass(Countable_Traversable::class),
      new ArgsMap_Simple(),
      \Traversable::class);

    $expected[] = new ATAPartial_StaticMethod(
      new \ReflectionMethod(HexColor::class, 'fromRgb'),
      new ArgsMap_Simple(),
      RgbColorInterface::class,
      HexColor::class);

    self::assertSameExportAndSort(
      $expected,
      $this->discoverPartials());
  }

  /**
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function discoverPartials(): array {
    return FixturesUtil::discoverPartials();
  }

  /**
   * @param array $expected
   * @param array $actual
   */
  private static function assertSameExportAndSort(array $expected, array $actual) {
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
