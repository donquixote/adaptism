<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter\DefinitionToATA;

use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Adaptism\Exception\Exception_ATABuilder;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class DefinitionToATA_ATAStaticFactory implements DefinitionToATAInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param array $definition
   *
   * @return \Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface
   *
   * @throws \Donquixote\Adaptism\Exception\Exception_ATABuilder
   */
  public function definitionGetPartial(array $definition): SpecificAdapterInterface {

    $reflMethod = DefinitionToATAUtil::definitionGetReflectionMethod(
      $definition,
      true,
      \ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC,
      \ReflectionMethod::IS_ABSTRACT);

    $parameters = $reflMethod->getParameters();

    $else = new \stdClass();

    $args = [];
    foreach ($parameters as $i => $parameter) {

      if ($else === $arg = $this->paramToValue->paramGetValue($parameter, $else)) {
        throw new Exception_ATABuilder("No value found for parameter $i.");
      }

      $args[] = $arg;
    }

    $instance = $reflMethod->invokeArgs(null, $args);

    if (!$instance instanceof SpecificAdapterInterface) {
      $class = $reflMethod->getDeclaringClass()->getName();
      $methodName = $reflMethod->getName();
      if ('object' !== $type = \gettype($instance)) {
        throw new Exception_ATABuilder("Expected method $class::$methodName() to return an ATAPartialInterface object, $type found instead.");
      }

      $wrongClass = \get_class($instance);
      throw new Exception_ATABuilder("Expected method $class::$methodName() to return an ATAPartialInterface object, $wrongClass object found instead.");
    }

    return $instance;
  }


}
