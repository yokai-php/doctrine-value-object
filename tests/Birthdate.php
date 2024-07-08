<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use DateTimeInterface;
use Yokai\DoctrineValueObject\DateTimeValueObject;

final class Birthdate implements DateTimeValueObject
{
    private DateTimeInterface $date;

    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public static function fromValue(DateTimeInterface $value): static
    {
        return new static($value);
    }

    public function toValue(): DateTimeInterface
    {
        return $this->date;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getAge(): int
    {
        return ((int)\date('Y')) - ((int) $this->date->format('Y'));
    }

    public function isAllowedToDrinkBeer(bool $parentsWillNotKnow): bool
    {
        if ($parentsWillNotKnow) {
            return true; // but be careful :)
        }

        return $this->getAge() >= 18;
    }
}
