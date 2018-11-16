<?php

namespace Tanigami\Specification;

use Doctrine\Common\Collections\Criteria;

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

    /**
     * {@inheritdoc}
     */
    public function criteria(): Criteria
    {
        return $this->one->criteria()->andWhere($this->other->criteria()->getWhereExpression());
    }

    /**
     * @return Specification
     */
    public function one(): Specification
    {
        return $this->one;
    }

    /**
     * @return Specification
     */
    public function other(): Specification
    {
        return $this->other;
    }
}
