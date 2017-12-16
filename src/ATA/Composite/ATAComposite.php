<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\Composite;

use Donquixote\Adaptism\ATA\ATAInterface;
use Donquixote\Adaptism\ATA\Complainee\ATAComplaineeInterface;

class ATAComposite implements ATACompositeInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\ATAInterface
   */
  private $ata;

  /**
   * @var \Donquixote\Adaptism\ATA\Complainee\ATAComplaineeInterface
   */
  private $complainee;

  /**
   * @param \Donquixote\Adaptism\ATA\ATAInterface $ata
   * @param \Donquixote\Adaptism\ATA\Complainee\ATAComplaineeInterface $complain
   */
  public function __construct(ATAInterface $ata, ATAComplaineeInterface $complain) {
    $this->ata = $ata;
    $this->complainee = $complain;
  }

  /**
   * @param object $original
   * @param string $destinationInterface
   *
   * @return object|null
   *   An instance of $destinationInterface, or
   *   NULL, if
   */
  public function adapt($original, $destinationInterface) {
    return $this->ata->adapt($original, $destinationInterface);
  }

  /**
   * @param object $original
   * @param string $destinationInterface
   * @param object $instead
   */
  public function complain($original, $destinationInterface, $instead) {
    return $this->complainee->complain($original, $destinationInterface, $instead);
  }
}
