<?php

namespace Tanigami\Specification;

class NoneOfSpecification extends Specification
{
    /**
     * @var Specification[]
     */
    public $specifications;

    /**
     * @param Specification[] ...$specifications
     */
    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($object): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($object)) {
                return false;
            }
        }

        return true;
    }
}
