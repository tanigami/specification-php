<?php

namespace Tanigami\Specification;

class OneOfSpecification extends Specification
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
                return true;
            }
        }

        return false;
    }
}
