<?php

namespace Tanigami\Specification;

/**
 * @template T
 * @extends Specification<T>
 */
class NotSpecification extends Specification
{
    /**
     * @var Specification<T>
     */
    private $specification;

    /**
     * @param Specification<T> $specification
     */
    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @param T $object
     */
    public function isSatisfiedBy($object): bool
    {
        return !$this->specification->isSatisfiedBy($object);
    }

    /**
     * @return Specification<T>
     */
    public function specification(): Specification
    {
        return $this->specification;
    }
}
