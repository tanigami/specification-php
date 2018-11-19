<?php

namespace Tanigami\Specification;

use BadMethodCallException;

abstract class Specification
{
    /**
     * @param mixed $object
     * @return bool
     */
    abstract public function isSatisfiedBy($object): bool;

    /**
     * @param string $alias
     * @return string
     */
    public function whereExpression(string $alias): string
    {
        throw new BadMethodCallException('Where expression is not supported');
    }

    /**
     * @param Specification $specification
     * @return AndSpecification
     */
    public function and(Specification $specification): AndSpecification
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * @param Specification $specification
     * @return OrSpecification
     */
    public function or(Specification $specification): OrSpecification
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * @return NotSpecification
     */
    public function not(): NotSpecification
    {
        return new NotSpecification($this);
    }
}
