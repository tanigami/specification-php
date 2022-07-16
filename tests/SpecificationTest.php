<?php

namespace Tanigami\Specification;

use PHPUnit\Framework\TestCase;
use stdClass;

class SpecificationTest extends TestCase
{
    public function testSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $this->assertTrue($trueSpec->isSatisfiedBy(new stdClass));
        $this->assertFalse($falseSpec->isSatisfiedBy(new stdClass));
    }

    public function testNotSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $notTrueSpec  = $trueSpec->not();
        $notFalseSpec = $falseSpec->not();
        $this->assertFalse($notTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertTrue($notFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testAndSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $trueAndTrueSpec  = $trueSpec->and($trueSpec);
        $trueAndFalseSpec = $trueSpec->and($falseSpec);
        $this->assertTrue($trueAndTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertFalse($trueAndFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testOrSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $trueOrTrueSpec  = $trueSpec->or($trueSpec);
        $trueOrFalseSpec = $trueSpec->or($falseSpec);
        $this->assertTrue($trueOrTrueSpec->isSatisfiedBy(new stdClass));
        $this->assertTrue($trueOrFalseSpec->isSatisfiedBy(new stdClass));
    }

    public function testAnyOfSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $this->assertTrue((new AnyOfSpecification($trueSpec, $trueSpec, $trueSpec))->isSatisfiedBy(new stdClass));
        $this->assertFalse((new AnyOfSpecification($trueSpec, $trueSpec, $falseSpec))->isSatisfiedBy(new stdClass));
    }

    public function testOneOfSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $this->assertFalse((new OneOfSpecification($falseSpec, $falseSpec, $falseSpec))->isSatisfiedBy(new stdClass));
        $this->assertTrue((new OneOfSpecification($falseSpec, $falseSpec, $trueSpec))->isSatisfiedBy(new stdClass));
    }

    public function testNoneOfSpecification()
    {
        $trueSpec  = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $this->assertTrue((new NoneOfSpecification($falseSpec, $falseSpec, $falseSpec))->isSatisfiedBy(new stdClass));
        $this->assertFalse((new NoneOfSpecification($falseSpec, $falseSpec, $trueSpec))->isSatisfiedBy(new stdClass));
    }

    public function testCriteriaComposition()
    {
        $trueSpec = new BoolSpecification(true);
        $falseSpec = new BoolSpecification(false);
        $compositeSpec =
            new AnyOfSpecification(
                $trueSpec->and($falseSpec)->or($trueSpec)->and($falseSpec),
                new OneOfSpecification($trueSpec, $falseSpec, $trueSpec),
                $trueSpec
            );
        $this->assertSame(
            '((((1) AND (0)) OR (1)) AND (0)) AND ((1) OR (0) OR (1)) AND (1)',
            $compositeSpec->whereExpression('a')
        );
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testWhereExpressionIsNotSupported()
    {
        (new BoolSpecification(true))->not()->whereExpression('a');
    }
}

class BoolSpecification extends Specification
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

    public function whereExpression(string $alias): string
    {
        return $this->bool ? '1' : '0';
    }
}
