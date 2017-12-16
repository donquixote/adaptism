<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\Annotation\Adapter;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use phpDocumentor\Reflection\TypeResolver;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;

class BetterReflectionTest extends TestCase {

  public function testBetterReflection() {

    $class = Countable_Traversable::class;
    $rc = (new BetterReflection)->classReflector()->reflect($class);
    $contextFinder = new NamespaceNodeToReflectionTypeContext();
    $namespaceNode = $rc->getDeclaringNamespaceAst();

    $context = $contextFinder->__invoke($namespaceNode);

    $this->assertSame(
      [
        'Adapter' => Adapter::class
      ],
      $context->getNamespaceAliases());

    $typeResolver = new TypeResolver();

    if (null === $type = $typeResolver->resolve('Adapter', $context)) {
      $this->fail("Type resolver returned null.");
    }

    $this->assertSame('\\' . Adapter::class, $type->__toString());
  }

}
