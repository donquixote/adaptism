<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Exception;

class Exception_ATA extends \Exception {

  /**
   * @param object $original
   * @param string $interface
   * @param mixed|null $instead
   *
   * @return self
   */
  public static function createWithInstead($original, $interface, $instead): self {

    $message = strtr(
      "Failed to create !destination\nfor !original.",
      [
        '!destination' => $interface,
        '!original' => \get_class($original) . ' object',
      ]);

    if (NULL !== $instead) {
      $message .= strtr(
        "\nFound !instead instead.",
        [
          '!instead' => self::formatValue($instead)
        ]);
    }

    return new self($message);
  }

  /**
   * @param object $original
   * @param string $interface
   * @param string|null $message_append
   *
   * @return self
   */
  public static function create($original, $interface, $message_append): self {

    $message = strtr(
      "Failed to create !destination\nfor !original.",
      [
        '!destination' => $interface,
        '!original' => \get_class($original) . ' object',
      ]);

    if (NULL !== $message_append) {
      $message .= "\n" . $message_append;
    }

    return new self($message);
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  private static function formatValue($value): string {

    switch ($type = \gettype($value)) {
      case 'object':
        return \get_class($value) . ' object';
      case 'array':
      case 'resource':
        return $type;
      default:
        return $type . ' (' . var_export($value, TRUE) . ')';
    }
  }

}
