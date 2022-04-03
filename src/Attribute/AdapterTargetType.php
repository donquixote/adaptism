<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

/**
 * Marks a parameter to act as the target type specifier.
 *
 * The parameter type must be 'string', and it should expect interface names or
 * class names as value.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class AdapterTargetType {

}
