# PHP Specification

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tanigami/specification-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tanigami/specification-php/build-status/master)

Basic classes for [Specification pattern](https://en.wikipedia.org/wiki/Specification_pattern) in PHP.

This package is based on the implementation in [carlosbuenosvinos/ddd](https://github.com/dddinphp/ddd).

# Installation

```
$ composer require tanigami/specification
```

## Usage example

```php
<?php

use Tanigami\Specification\Specification;

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

$paid = new PaidOrderSpecification;
$unshipped = new UnshippedOrderSpecification;

$paid->and($unshipped)->isSatisfiedBy(new Order); // => true

```
