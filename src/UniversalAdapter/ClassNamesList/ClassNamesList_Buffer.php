<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ClassNamesList;

class ClassNamesList_Buffer implements ClassNamesListInterface {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\ClassNamesList\ClassNamesListInterface
   */
  private $decorated;

  /**
   * @var null|string[]
   */
  private $buffer;

  /**
   * @param \Donquixote\Adaptism\UniversalAdapter\ClassNamesList\ClassNamesListInterface $decorated
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
