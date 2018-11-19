<?php

namespace Tanigami\Specification;

class OrSpecification extends Specification
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
        return $this->one->isSatisfiedBy($object) || $this->other->isSatisfiedBy($object);
    }

    /**
     * {@inheritdoc}
     */
    public function whereExpression(string $alias): string
    {
        return sprintf(
            sprintf(
                '(%s) OR (%s)',
                $this->one()->whereExpression($alias),
                $this->other()->whereExpression($alias)
            )
        );
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
