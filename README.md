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

### String

Imagine you have an application that stores phone numbers in database.
You will often have to determine the country from which the number is originated.

You can centralize this logic in a `PhoneNumber` **string value object**.
The object will be **stored as a string** in the database but **hydrated back to this object** by Doctrine.

```php
use Yokai\DoctrineValueObject\StringValueObject;

class PhoneNumber implements StringValueObject
{
    private string $number;

    public function __construct(string $number)
    {
        if (\strpos($number, '+') !== 0) {
            $number = '+33' . \substr($number, 1);
        }

        $this->number = $number;
    }

    public static function fromValue(string $value): self
    {
        return new self($value);
    }

    public function toValue(): string
    {
        return $this->number;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCountry(): string
    {
        return \substr($this->number, 1, 2);
    }
}
```

### Collection

Imagine that in this application, you will sometimes have to store multiple phone numbers in a property.
You can centralize this type hinting in a `PhoneNumbers` **collection value object**.

The object will be **stored as a json** in the database but **hydrated back to this object** by Doctrine.

```php
use Yokai\DoctrineValueObject\CollectionValueObject;

class PhoneNumbers implements CollectionValueObject, \Countable, \IteratorAggregate
{
    private array $numbers;

    /**
     * @param PhoneNumber[] $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    public static function fromValue(array $value): self
    {
        return new static(
            \array_map([PhoneNumber::class, 'fromValue'], $value)
        );
    }

    public function toValue(): array
    {
        return $this->numbers;
    }

    public function count(): int
    {
        return \count($this->numbers);
    }
    
    /**
     * @return \ArrayIterator<PhoneNumber>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->numbers);
    }

    /**
     * @return PhoneNumber[]
     */
    public function getNumbers(): array
    {
        return $this->numbers;
    }
}
```

### Integer

Imagine you have an application that birth year of users.
You will often have to determine the age of the user, and put rules on this value.

You can centralize this logic in a `BirthYear` **integer value object**.
The object will be **stored as a integer** in the database but **hydrated back to this object** by Doctrine.

```php
use Yokai\DoctrineValueObject\IntegerValueObject;

class BirthYear implements IntegerValueObject
{
    private int $year;

    /**
     * @param int $year
     */
    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public static function fromValue(int $value): self
    {
        return new static($value);
    }

    public function toValue(): int
    {
        return $this->year;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getAge(): int
    {
        return ((int)\date('Y')) - $this->year;
    }

    public function isAllowedToDrinkBeer(bool $parentsWillNotKnow): int
    {
        if ($parentsWillNotKnow) {
            return true; // but be careful :)
        }

        return $this->getAge() >= 18;
    }
}
```


## Contribution

Please feel free to open an [issue](https://github.com/yokai-php/doctrine-value-object/issues)
or a [pull request](https://github.com/yokai-php/doctrine-value-object/pulls).

The library was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/doctrine-value-object/contributors).


## License

This library is under MIT [LICENSE](LICENSE).
