<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Value;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\GetService;

class TimeConverter {

  public function __construct(
    #[GetService] private \DateTimeZone $timeZone,
  ) {}

  #[Adapter]
  public function convert(
    #[Adaptee] Timestamp $timestamp,
  ): LocalDateTimeString {
    $date = new \DateTime('now', $this->timeZone);
    $date->setTimestamp($timestamp->getTimestamp());
    return new LocalDateTimeString($date->format('Y-m-d\TH:i:s'));
  }

}
