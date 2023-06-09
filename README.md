# Value Objects for Doctrine ORM simplified

[![Tests](https://img.shields.io/github/actions/workflow/status/yokai-php/doctrine-value-object/tests.yml?branch=main&style=flat-square&label=tests)](https://github.com/yokai-php/doctrine-value-object/actions)
[![Coverage](https://img.shields.io/codecov/c/github/yokai-php/doctrine-value-object?style=flat-square)](https://codecov.io/gh/yokai-php/doctrine-value-object)
[![Contributors](https://img.shields.io/github/contributors/yokai-php/doctrine-value-object?style=flat-square)](https://github.com/yokai-php/doctrine-value-object/graphs/contributors)

[![Latest Stable Version](https://img.shields.io/packagist/v/yokai/doctrine-value-object?style=flat-square)](https://packagist.org/packages/yokai/doctrine-value-object)
[![Downloads Monthly](https://img.shields.io/packagist/dm/yokai/doctrine-value-object?style=flat-square)](https://packagist.org/packages/yokai/doctrine-value-object/stats)

This library offer you an easy way to handle **value objects** in a **Doctrine ORM** application.

It will save you the creation of Doctrine types for every different value object types you have.


## Installation

```bash
composer require yokai/doctrine-value-object
```


## Setup

You will have to register your value object types to the Doctrine type system.

```php
use Doctrine\DBAL\Types\Type;
use Yokai\DoctrineValueObject\Doctrine\Types;

(new Types(['doctrine_type_name' => MyValueObject::class]))
    ->register(Type::getTypeRegistry());
```

If you are using **Symfony**, you can register your value object types in the kernel construction.

```php
use Doctrine\DBAL\Types\Type;
use Yokai\DoctrineValueObject\Doctrine\Types;

class Kernel extends BaseKernel
{
    private const DOCTRINE_VALUE_OBJECTS = [
        'doctrine_type_name' => MyValueObject::class,
    ];

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        (new Types(self::DOCTRINE_VALUE_OBJECTS))->register(Type::getTypeRegistry());
    }
}
```


## Usage

After you completed setup you will be able to use your value object types type in any of your entities:

```php
<?php

namespace Yokai\DoctrineValueObject\Tests;

use Doctrine\ORM\Mapping as ORM;

final class Entity
{
    /**
     * @ORM\Column(type="doctrine_type_name")
     */
    public MyValueObject $status;
}
```


## Value Object Types

Value objects are wrappers around existing doctrine types.

Let's see what wrappers exist and give you an example for each.

### String

Imagine you have an application that stores phone numbers in database.
You will often have to determine the country from which the number is originated.

You can centralize this logic in a `PhoneNumber` **string value object**.
The object will be **stored as a string** in the database but **hydrated back to this object** by Doctrine.

See [PhoneNumber code](tests/PhoneNumber.php) in tests.


### DateTime

Imagine you have an application that stores users birthdate.
You will often have to determine the age of the user, and put rules on this value.

You can centralize this logic in a `Birthdate` **datetime value object**.
The object will be **stored as datetime immutable** in the database but **hydrated back to this object** by Doctrine.

See [Birthdate code](tests/Birthdate.php) in tests.


### Integer

Imagine you have an application that stores user status.
You will often have to put rules on this value.

You can centralize this logic in a `Status` **integer value object**.
The object will be **stored as a integer** in the database but **hydrated back to this object** by Doctrine.

See [Status code](tests/Status.php) in tests.


### Object

:warning: **If you are looking for value objects with multiple properties each stored in a table column:
please use [Doctrine Embeddables](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html).**

:warning: **If you are looking for polymorphic value objects stored as JSON:
please use [dunglas/doctrine-json-odm](https://github.com/dunglas/doctrine-json-odm).**

Imagine you have an application that stores user notifications preferences.
There is multiple information you want to store, as a JSON object.

You can centralize this logic in a `Notifications` **object value object**.
The object will be **stored as a json object** in the database but **hydrated back to this object** by Doctrine.

See [Notifications code](tests/Notifications.php) in tests.


### Collection

Imagine that you have an application that have to store multiple phone numbers in a property.
You can centralize this type hinting in a `PhoneNumbers` **collection value object**.

The object will be **stored as a json array** in the database but **hydrated back to this object** by Doctrine.

See [PhoneNumbers code](tests/PhoneNumbers.php) in tests.


## Contribution

Please feel free to open an [issue](https://github.com/yokai-php/doctrine-value-object/issues)
or a [pull request](https://github.com/yokai-php/doctrine-value-object/pulls).

The library was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/doctrine-value-object/contributors).


## License

This library is under MIT [LICENSE](LICENSE).
