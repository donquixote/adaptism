<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

class GetService {

  /**
   * Constructor.
   *
   * @param string|null $id
   *   Id of the service, or NULL to use interface name.
   */
  public function __construct(
    private ?string $id = NULL,
  ) {}

  public function getId(): ?string {
    return $this->id;
  }

}
