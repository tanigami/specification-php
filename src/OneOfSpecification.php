<?php

namespace Tanigami\Specification;

/**
 * @template T
 * @extends Specification<T>
 */
class OneOfSpecification extends Specification
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
            if ($specification->isSatisfiedBy($object)) {
                return true;
            }
        }

        return false;
    }

    public function whereExpression(string $alias): string
    {
        return implode(' OR ', array_map(
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
