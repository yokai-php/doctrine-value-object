<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

interface StringValueObject
{
    public static function fromValue(string $value): static;

    public function toValue(): string;
}
