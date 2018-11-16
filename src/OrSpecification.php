<?php

namespace Tanigami\Specification;

class OrSpecification extends Specification
{
    /**
     * @var Specification
     */
    public $one;

    /**
     * @var Specification
     */
    public $other;

    /**
     * @param Specification $one
     * @param Specification $other
     */
    public function __construct(Specification $one, Specification $other)
    {
        $this->one   = $one;
        $this->other = $other;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($object): bool
    {
        return $this->one->isSatisfiedBy($object) || $this->other->isSatisfiedBy($object);
    }
}
