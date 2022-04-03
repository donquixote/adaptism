<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Callback;

class Callback_Curry implements CallbackInterface {

  /**
   * Constructor.
   *
   * @param callable $decorated
   * @param array $args
   *
   * @noinspection PhpDocSignatureInspection
   */
  public function __construct(
    private array|string|object $decorated,
    private array $args,
  ) {}

  /**
   * @param ...$args
   *
   * @return mixed
   */
  public function __invoke(...$args): mixed {
    return ($this->decorated)(...$this->args + $args);
  }

}
