<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

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
