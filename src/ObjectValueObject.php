<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

interface ObjectValueObject
{
    public static function fromValue(array $value): static;

    public function toValue(): array;
}
