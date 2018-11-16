<?php

namespace Tanigami\Specification;

use Doctrine\Common\Collections\Criteria;

class OneOfSpecification extends Specification
{
    /**
     * @var Specification[]
     */
    private $specifications;

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
    public function criteria(): Criteria
    {
        /** @var Criteria $criteria */
        $criteria = null;
        foreach ($this->specifications as $specification) {
            if (is_null($criteria)) {
                $criteria = $specification->criteria();
            } else {
                $criteria = $criteria->orWhere($specification->criteria()->getWhereExpression());
            }
        }

        return $criteria;
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

    /**
     * @return Specification[]
     */
    public function specifications(): array
    {
        return $this->specifications;
    }
}
