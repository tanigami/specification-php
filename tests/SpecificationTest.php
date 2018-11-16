<?php

namespace Tanigami\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
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
        $visitor = new Visitor();
        $compositeSpec->criteria()->getWhereExpression()->visit($visitor);
        $this->assertSame(
            '((((((bool = 1) AND (bool = 0)) OR (bool = 1)) AND (bool = 0)) AND (((bool = 1) OR (bool = 0)) OR (bool = 1))) AND (bool = 1))',
            $visitor->trace()
        );
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCriteriaIsNotSupported()
    {
        (new BoolSpecification(true))->not()->criteria();
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

    public function criteria(): Criteria
    {
        return new Criteria(Criteria::expr()->eq('bool', $this->bool));
    }
}

class Visitor extends ExpressionVisitor
{
    private $trace;

    public function walkComparison(Comparison $comparison)
    {
        $this->trace .= '(';
        $this->trace .= $comparison->getField();
        $this->trace .= ' ' . $comparison->getOperator() . ' ';
        $this->trace .= $this->walkValue($comparison->getValue());
        $this->trace .= ')';
    }

    public function walkCompositeExpression(CompositeExpression $expr)
    {
        $this->trace .= '(';
        foreach ($expr->getExpressionList() as $i => $child) {
            if ($i !== 0) {
                $this->trace .= (' ' . $expr->getType() . ' ');
            }
            $expressionList[] = $this->dispatch($child);
        }
        $this->trace .= ')';
    }

    public function walkValue(Value $value)
    {
        return $value->getValue() ? '1' : '0';
    }

    public function trace()
    {
        return $this->trace;
    }
}
