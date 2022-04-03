<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

interface AdapterDefinitionListInterface {

  /**
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface[]
   *
   * @throws \ReflectionException
   */
  public function getDefinitions(): array;

}
