<?php

namespace Tanigami\Specification;

class AnyOfSpecification extends Specification
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
    public function isSatisfiedBy($object): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($object)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function whereExpression(string $alias): string
    {
        return implode(' AND ', array_map(
            function (Specification $specification) use ($alias) {
                return '(' . $specification->whereExpression($alias) . ')';
            },
            $this->specifications
        ));
    }

    /**
     * @return Specification[]
     */
    public function specifications(): array
    {
        return $this->specifications;
    }
}
