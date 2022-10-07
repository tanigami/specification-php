<?php

namespace Tanigami\Specification;

/**
 * @template T
 * @extends Specification<T>
 */
class AnyOfSpecification extends Specification
{
    /**
     * @var Specification<T>[]
     */
    private $specifications;

    /**
     * @param Specification<T> ...$specifications
     */
    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * @param T $object
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

    public function whereExpression(string $alias): string
    {
        return implode(' AND ', array_map(
            static function (Specification $specification) use ($alias) {
                return '(' . $specification->whereExpression($alias) . ')';
            },
            $this->specifications
        ));
    }

    /**
     * @return Specification<T>[]
     */
    public function specifications(): array
    {
        return $this->specifications;
    }
}
