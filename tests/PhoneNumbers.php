<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Yokai\DoctrineValueObject\CollectionValueObject;

final class PhoneNumbers implements CollectionValueObject
{
    private array $numbers;

    /**
     * @param PhoneNumber[] $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    public static function fromValue(array $value): static
    {
        return new static(
            \array_map([PhoneNumber::class, 'fromValue'], $value)
        );
    }

    public function toValue(): array
    {
        return \array_map(fn(PhoneNumber $number) => $number->toValue(), $this->numbers);
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
