# PHP Specification

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/build-status/master)

Basic classes for [Specification pattern](https://en.wikipedia.org/wiki/Specification_pattern) in PHP. On top of the typical set of `and`, `or` and `not` specificaitons, `anyOf`, `oneOf`, `noneOf` specifications are proposed.

This package is based on the implementation in [carlosbuenosvinos/ddd](https://github.com/dddinphp/ddd).

## Installation

```
$ composer require tanigami/specification
```

## Usage example

```php
<?php

use Tanigami\Specification\Specification;
use Tanigami\Specification\OneOfSpecification;

class Order
{
    public function isPaid()
    {
        return true;
    }

    public function isShipped()
    {
        return false;
    }
    
    public function isCancelled()
    {
        return false;
    }
}

class UnshippedOrderSpecification extends Specification
{
    public function isSatisfiedBy($order): bool
    {
        return !$order->isShipped();
    }
}

class PaidOrderSpecification extends Specification
{
    public function isSatisfiedBy($order): bool
    {
        return $order->isPaid();
    }
}

class CancelledOrderSpecification extends Specification
{
    public function isSatisfiedBy($order): bool
    {
        return $order->isCancelled();
    }
}

$paid = new PaidOrderSpecification;
$unshipped = new UnshippedOrderSpecification;
$cancelled = new CancelledOrderSpecification;


$paid->and($unshipped)->isSatisfiedBy(new Order); // => true
(new OneOfSpecification($paid, $unshipped, $cancelled))->isSatisfiedBy(new Order); // => true
```
