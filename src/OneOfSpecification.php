<?php

namespace Tanigami\Specification;

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
     * {@inheritdoc}
     */
    public function whereExpression(string $alias): string
    {
        return implode(' OR ', array_map(
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
