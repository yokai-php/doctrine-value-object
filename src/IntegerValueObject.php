<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

interface IntegerValueObject
{
    public static function fromValue(int $value): static;

    public function toValue(): int;
}
