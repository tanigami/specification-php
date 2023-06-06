<?php

namespace Tanigami\Specification;

use BadMethodCallException;

/**
 * @template T
 */
abstract class Specification
{
    /**
     * @param T $object
     */
    abstract public function isSatisfiedBy($object): bool;

    public function whereExpression(string $alias): string
    {
        throw new BadMethodCallException('Where expression is not supported');
    }

    /**
     * @param Specification<T> $specification
     * @return AndSpecification<T>
     */
    public function and(Specification $specification): AndSpecification
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * @param Specification<T> $specification
     * @return OrSpecification<T>
     */
    public function or(Specification $specification): OrSpecification
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * @return NotSpecification<T>
     */
    public function not(): NotSpecification
    {
        return new NotSpecification($this);
    }
}
