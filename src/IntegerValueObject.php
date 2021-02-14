<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

/**
 * todo php 8 : fromValue with static return type + impacts on descendants
 */
interface IntegerValueObject
{
    public static function fromValue(int $value): self;

    public function toValue(): int;
}
