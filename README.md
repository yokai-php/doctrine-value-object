# Doctrine Value Objects simplified

This library offer you an easy way to handle **value objects** in a **Doctrine ORM** application.

It will save you the creation of Doctrine types for every different value object types you have.

**If you are looking for Value Objects with multiple properties to store : please use Doctrine Embeddables.**


## Usage

You will have to register your value object types to the Doctrine type system.

```php
use Doctrine\DBAL\Types\Type;
use Yokai\DoctrineValueObject\Doctrine\Types;

(new Types(['doctrine_type_name' => MyValueObject::class]))
    ->register(Type::getTypeRegistry());
```

If you are using Symfony, you can register your value object types in the kernel construction.

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

## Value Object Types

Value objects are wrappers around existing doctrine types.

Let's see what wrappers exist and give you an example for each.

### String

Imagine you have an application that stores phone numbers in database.
You will often have to determine the country from which the number is originated.

You can centralize this logic in a `PhoneNumber` **string value object**.
The object will be **stored as a string** in the database but **hydrated back to this object** by Doctrine.

See [PhoneNumber code](tests/PhoneNumber.php) in tests.


### Collection

Imagine that in this application, you will sometimes have to store multiple phone numbers in a property.
You can centralize this type hinting in a `PhoneNumbers` **collection value object**.

The object will be **stored as a json** in the database but **hydrated back to this object** by Doctrine.

See [PhoneNumbers code](tests/PhoneNumbers.php) in tests.


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


## Contribution

Please feel free to open an [issue](https://github.com/yokai-php/doctrine-value-object/issues)
or a [pull request](https://github.com/yokai-php/doctrine-value-object/pulls).

The library was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/doctrine-value-object/contributors).


## License

This library is under MIT [LICENSE](LICENSE).
