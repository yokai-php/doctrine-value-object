<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

use DateTimeInterface;

interface DateTimeValueObject
{
    public static function fromValue(DateTimeInterface $value): static;

    public function toValue(): DateTimeInterface;
}
