<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA;

use Donquixote\Adaptism\ATA\Partial\ATAPartial_SmartChain;
use Donquixote\Adaptism\ATA\Partial\ATAPartialInterface;
use Donquixote\Adaptism\Exception\Exception_MisbehavingATA;

class ATA implements ATAInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials) {
    return new self(new ATAPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\Adaptism\ATA\Partial\ATAPartialInterface $partial
   */
  public function __construct(ATAPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param mixed $source
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function adapt($source, $interface) {

    if ($source instanceof $interface) {
      return $source;
    }

    try {
      return $this->partial->adapt($source, $interface, $this);
    }
    catch (Exception_MisbehavingATA $e) {
      // @todo Do something!
      unset($e);
      return null;
    }
  }
}
