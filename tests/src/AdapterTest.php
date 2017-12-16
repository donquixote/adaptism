<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\ATA\ArgsMap\ArgsMap_Simple;
use Donquixote\Adaptism\ATA\ATA_PartialsList;
use Donquixote\Adaptism\ATA\ATA_SmartChain;
use Donquixote\Adaptism\ATA\ATABuilder;
use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_ClassInstance;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_Seed_Neutral_Object;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_Seed_Neutral_Objects;
use Donquixote\Adaptism\ATA\Partial\ATAPartial_StaticMethod;
use Donquixote\Adaptism\Seed\Seed_Neutral;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;

class AdapterTest extends \PHPUnit_Framework_TestCase {

  public function testSimpleATA() {

    $partial = new ATAPartial_Seed_Neutral_Object((object)['x' => 'y']);

    $this->assertSame(
      \stdClass::class,
      $partial->getResultType());

    $this->assertTrue(
      $partial->acceptsSourceClass(Seed_Neutral::class));

    $this->assertTrue(
      $partial->providesResultType(\stdClass::class));

    $ata = ATABuilder::create()
      ->withCustomATA($partial)
      ->build();

    $seed = new Seed_Neutral();

    $this->assertEquals(
      ['x' => 'y'],
      (array)$partial->adapt(
        $seed,
        \stdClass::class,
        $ata));

    $this->assertEquals(
      ['x' => 'y'],
      (array)$ata->adapt(
        $seed,
        \stdClass::class));
  }

  public function testMore() {

    $list = ATABuilder::create()
      ->withCustomATA(
        $partial = new ATAPartial_Seed_Neutral_Objects(
          [
            new \ReflectionFunction('strtolower'),
            new \ReflectionClass(self::class),
          ]))
      ->buildPartialsList();

    $this->assertSame(
      ['object' => 'object'],
      $list->getTypes());

    $ata = new ATA_PartialsList($list);

    $seed = new Seed_Neutral();

    $this->assertSame('object', $partial->getResultType());

    $this->assertTrue(
      $partial->providesResultType(\Reflector::class));

    $this->assertTrue(
      $partial->acceptsSourceClass(Seed_Neutral::class));

    $result = $partial->adapt($seed, \Reflector::class,$ata);

    if (!$result instanceof \ReflectionClass) {
      $resulttype = \gettype($result);
      $this->fail("Instance of ReflectionClass expected, $resulttype found instead.");
      return;
    }

    $result = $ata->adapt(
      $seed,
      \Reflector::class);

    if (!$result instanceof \ReflectionClass) {
      $resulttype = \gettype($result);
      $this->fail("Instance of ReflectionClass expected, $resulttype found instead.");
      return;
    }
  }

  public function testTraversableToCountable() {

    $ata = $this->buildAta();

    $traversable = \call_user_func(
      function() {
        yield 'first';
        yield 'second';
        yield 'third';
      });

    if (!$traversable instanceof \Traversable) {
      $this->fail("Failed to create a traversable.");
      return;
    }

    $countable = $ata->adapt($traversable, \Countable::class);

    if (!$countable instanceof \Countable) {
      if (null === $countable) {
        $this->fail("Failed to adapt as Countable.");
      }
      else {
        $this->fail("Unexpected return value from ATA.");
      }
      return;
    }

    $this->assertSame(3, $countable->count());
  }

  public function testRgbToHex() {

    $ata = $this->buildAta();

    $colors = [
      'ff0000' => new RgbColor(255, 0, 0),
      '0000ff' => new RgbColor(0, 0, 255),
      'ffff00' => new RgbColor(255, 255, 0),
      '404040' => new RgbColor(64, 64, 64),
    ];

    foreach ($colors as $hexCode => $rgb) {
      $hex = $ata->adapt($rgb, HexColorInterface::class);
      if (!$hex instanceof HexColorInterface) {
        $this->fail("Misbehaving adapter: Must return a hex color, but did not.");
        return;
      }
      $this->assertSame((string)$hexCode, $hex->getHexCode());
    }
  }

  /**
   * @dataProvider providerATA()
   *
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   */
  public function testSeed(ATAInterface $ata) {

    # $ata = $this->buildAta();

    $seed = new Seed_Neutral();

    $this->assertEquals(
      ['x' => 'y'],
      (array)$ata->adapt(
        $seed,
        \stdClass::class));

    $result = $ata->adapt(
      $seed,
      \Reflector::class);

    if (!$result instanceof \ReflectionClass) {
      $resulttype = \gettype($result);
      $this->fail("Instance of ReflectionClass expected, $resulttype found instead.");
      return;
    }

    $this->assertSame(
      self::class,
      $result->getName());

    $result = $ata->adapt(
      $seed,
      \ReflectionFunction::class);

    if (!$result instanceof \ReflectionFunction) {
      $this->fail("Instance of ReflectionFunction expected.");
      return;
    }

    $this->assertSame(
      'strtolower',
      $result->getName());
  }

  /**
   * @return array[]
   */
  public function providerATA() {
    $sets = [];
    $sets[] = [
      $this->buildAta(),
    ];
    $sets[] = [
      $this->buildNewAta(),
    ];
    return $sets;
  }

  private function buildNewAta() {

    return ATABuilder::create()
      ->withCustomATA(
        new ATAPartial_Seed_Neutral_Object((object)['x' => 'y']))
      ->withCustomATA(
        new ATAPartial_Seed_Neutral_Objects(
          [
            new \ReflectionFunction('strtolower'),
            new \ReflectionClass(self::class),
          ]))
      ->withClassFilesIA(FixturesUtil::getClassFilesIA())
      ->build();
  }

  /**
   * @return \Donquixote\Adaptism\ATA\ATAInterface
   */
  private function buildAta() {
    return new ATA_SmartChain(
      $this->buildPartials());
  }

  /**
   * @return \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[]
   */
  private function buildPartials() {

    $partials = [];

    $partials[] = new ATAPartial_ClassInstance(
      new \ReflectionClass(Countable_Traversable::class),
      new ArgsMap_Simple(),
      \Traversable::class);

    $partials[] = new ATAPartial_StaticMethod(
      new \ReflectionMethod(HexColor::class, 'fromRgb'),
      new ArgsMap_Simple(),
      RgbColorInterface::class,
      HexColorInterface::class);

    $partials[] = new ATAPartial_Seed_Neutral_Object((object)['x' => 'y']);

    $partials[] = new ATAPartial_Seed_Neutral_Objects(
      [
        new \ReflectionFunction('strtolower'),
        new \ReflectionClass(self::class),
      ]);

    return $partials;
  }

}
