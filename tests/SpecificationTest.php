<?php

namespace Tanigami\Specification;

use PHPUnit\Framework\TestCase;
use stdClass;

class SpecificationTest extends TestCase
{
    public function testSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $this->assertTrue($trueSpec->isSatisfiedBy(new stdClass));
        $this->assertFalse($falseSpec->isSatisfiedBy(new stdClass));
    }

    public function testNotSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $notTrueSpec  = $trueSpec->not();
        $notFalseSpec = $falseSpec->not();
        $this->assertFalse($notTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertTrue($notFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testAndSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $trueAndTrueSpec  = $trueSpec->and($trueSpec);
        $trueAndFalseSpec = $trueSpec->and($falseSpec);
        $this->assertTrue($trueAndTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertFalse($trueAndFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testOrSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $trueOrTrueSpec  = $trueSpec->or($trueSpec);
        $trueOrFalseSpec = $trueSpec->or($falseSpec);
        $this->assertTrue($trueOrTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertTrue($trueOrFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testAnyOfSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $this->assertTrue((new AnyOfSpecification($trueSpec, $trueSpec, $trueSpec))->isSatisfiedBy(new stdClass));
        $this->assertFalse((new AnyOfSpecification($trueSpec, $trueSpec, $falseSpec))->isSatisfiedBy(new stdClass));
    }

    public function testOneOfSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $this->assertFalse((new OneOfSpecification($falseSpec, $falseSpec, $falseSpec))->isSatisfiedBy(new stdClass));
        $this->assertTrue((new OneOfSpecification($falseSpec, $falseSpec, $trueSpec))->isSatisfiedBy(new stdClass));
    }

    public function testNoneOfSpecification()
    {
        $trueSpec  = new FakeSpecification(true);
        $falseSpec = new FakeSpecification(false);
        $this->assertTrue((new NoneOfSpecification($falseSpec, $falseSpec, $falseSpec))->isSatisfiedBy(new stdClass));
        $this->assertFalse((new NoneOfSpecification($falseSpec, $falseSpec, $trueSpec))->isSatisfiedBy(new stdClass));
    }
}

class FakeSpecification extends Specification
{
    private $bool;

    public function __construct(bool $bool)
    {
        $this->bool = $bool;
    }

    public function isSatisfiedBy($object): bool
    {
        return $this->bool;
    }
}
