<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

/**
 * todo php 8 : fromValue with static return type + impacts on descendants
 */
interface StringValueObject
{
    public static function fromValue(string $value): self;

    public function toValue(): string;
}
