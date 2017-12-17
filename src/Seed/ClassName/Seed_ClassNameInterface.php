<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Seed\ClassName;

/**
 * Object that will be converted into an instance of the class name by an adapter.
 */
interface Seed_ClassNameInterface {

  /**
   * Gets the class or interface name.
   *
   * @return string
   */
  public function getClassName(): string;

}
