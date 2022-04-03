<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

interface AdapterMapInterface {

  /**
   * @param class-string|null $source_type
   * @param class-string|null $result_type
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface[]
   */
  public function getSuitableAdapters(?string $source_type, ?string $result_type): array;

}
