<?php

namespace Tanigami\Specification;

/**
 * @template T
 * @extends Specification<T>
 */
class AndSpecification extends Specification
{
    /**
     * @var Specification<T>
     */
    private $one;

    /**
     * @var Specification<T>
     */
    private $other;

    /**
     * @param Specification<T> $one
     * @param Specification<T> $other
     */
    public function __construct(Specification $one, Specification $other)
    {
        $this->one   = $one;
        $this->other = $other;
    }

    /**
     * @param T $object
     */
    public function isSatisfiedBy($object): bool
    {
        return $this->one->isSatisfiedBy($object) && $this->other->isSatisfiedBy($object);
    }

    public function whereExpression(string $alias): string
    {
        return sprintf(
            '(%s) AND (%s)',
            $this->one()->whereExpression($alias),
            $this->other()->whereExpression($alias)
        );
    }

    /**
     * @return Specification<T>
     */
    public function one(): Specification
    {
        return $this->one;
    }

    /**
     * @return Specification<T>
     */
    public function other(): Specification
    {
        return $this->other;
    }
}
