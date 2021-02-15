<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

/**
 * todo php 8 : fromValue with static return type + impacts on descendants
 */
interface ObjectValueObject
{
    public static function fromValue(array $value): self;

    public function toValue(): array;
}
