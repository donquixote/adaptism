<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Seed\ClassName;

class Seed_ClassName implements Seed_ClassNameInterface {

  /**
   * @var string
   */
  private $class;

  /**
   * @param string $class
   */
  public function __construct($class) {
    $this->class = $class;
  }

  /**
   * Gets the class or interface name.
   *
   * @return string
   */
  public function getClassName(): string {
    return $this->class;
  }
}
