<?php

namespace Tanigami\Specification;

class AndSpecification extends Specification
{
    /**
     * @var Specification
     */
    private $one;

    /**
     * @var Specification
     */
    private $other;

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
        return $this->one->isSatisfiedBy($object) && $this->other->isSatisfiedBy($object);
    }
}
