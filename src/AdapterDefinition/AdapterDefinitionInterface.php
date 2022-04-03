<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinition;

use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface;

interface AdapterDefinitionInterface {

  /**
   * Gets the result type, or a common ancestor of all result types.
   *
   * @return class-string|null
   */
  public function getResultType(): ?string;

  /**
   * @return class-string|null
   */
  public function getSourceType(): ?string;

  /**
   * @param class-string $resultType
   *
   * @return bool
   */
  public function providesResultType(string $resultType): bool;

  /**
   * @param class-string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass(string $sourceClass): bool;

  /**
   * @return int
   */
  public function getSpecifity(): int;

  /**
   * @return \Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface
   */
  public function getFactory(): AdapterFromContainerInterface;

}
