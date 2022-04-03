<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\ClassNamesList;

class ClassNamesList_Cache implements ClassNamesListInterface {

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\ClassNamesList\ClassNamesListInterface
   */
  private $decorated;

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
    // @todo Implement cache!
    return $this->decorated->getClassNames();
  }

}
