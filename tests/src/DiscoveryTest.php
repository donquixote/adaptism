<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\Annotation\Adapter;
use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_Simple;
use Donquixote\Adaptism\ATA\ATABuilder;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_ClassInstance;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_Seed_Neutral_Object;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_StaticMethod;
use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurences_BetterReflection;
use Donquixote\Adaptism\Discovery\ClassFileToOccurences\ClassFileToOccurencesUtil;
use Donquixote\Adaptism\Discovery\Occurence\Occurence;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\BetterReflection;

class DiscoveryTest extends TestCase {

  public function testClassFilesIA() {
    $classFilesIA = FixturesUtil::getClassFilesIA();
    self::assertSame(
      [
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Hex/HexColor.php' => HexColor::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Hex/HexColorInterface.php' => Fixtures\Color\Hex\HexColorInterface::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Rgb/RgbColor.php' => Fixtures\Color\Rgb\RgbColor::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Rgb/RgbColorInterface.php' => RgbColorInterface::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Countable/Countable_Callback.php' => Fixtures\Countable\Countable_Callback::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Countable/Countable_Traversable.php' => Countable_Traversable::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/FixturesUtil.php' => FixturesUtil::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/GeneratorCollection.php' => Fixtures\GeneratorCollection::class,
      ],
      iterator_to_array($classFilesIA->getIterator()));
  }

  public function testFileContentMightHaveAnnotation() {
    $file = '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Hex/HexColor.php';
    $fileContent = file_get_contents($file);
    self::assertTrue(ClassFileToOccurencesUtil::fileContentMightHaveAnnotation(
      $fileContent,
      Adapter::class));
  }

  public function testReturnTypeClassNames() {
    $reflFunction = (new BetterReflection())->classReflector()->reflect(HexColor::class)->getMethod('fromRgb');
    self::assertSame(
      [HexColor::class],
      ClassFileToOccurencesUtil::functionGetReturnTypeClassNames($reflFunction));
  }

  public function testClassFileToOccurences() {

    $classFileToOccurences = ClassFileToOccurences_BetterReflection::create();

    $occurence0 = (new Occurence(
      [
        'type' => 'adapterStaticFactory',
        'class' => HexColor::class,
        'method' => 'fromRgb',
      ]))
      ->withReturnTypeClassName(HexColor::class);

    $occurence1 = Occurence::fromStaticMethod(
      HexColor::class,
      'fromRgb',
      'adapter')
      ->withReturnTypeClassName(HexColor::class);

    self::assertSameExport($occurence0, $occurence1);

    self::assertSameExport(
      [$occurence0],
      $classFileToOccurences->classFileGetOccurences(
        HexColor::class,
        '/home/lemonhead/projects/phplib/adaptism/tests/src/Fixtures/Color/Hex/HexColor.php'));
  }

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

  public function _testDiscoverPartials() {

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
      FixturesUtil::discoverPartials());
  }

  /**
   * @param mixed $expected
   * @param mixed $actual
   */
  private static function assertSameExport($expected, $actual) {
    self::assertSame(
      var_export($expected, true),
      var_export($actual, true));
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
