<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\ATA\ClassNamesList;

class ClassNamesList_Buffer implements ClassNamesListInterface {

  /**
   * @var \Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesListInterface
   */
  private $decorated;

  /**
   * @var null|string[]
   */
  private $buffer;

  /**
   * @param \Donquixote\Adaptism\ATA\ClassNamesList\ClassNamesListInterface $decorated
   */
  public function __construct(ClassNamesListInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[]
   */
  public function getClassNames(): array {
    return $this->buffer
      ?? $this->buffer = $this->decorated->getClassNames();
  }

}
