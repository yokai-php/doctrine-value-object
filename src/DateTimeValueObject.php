<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

use DateTimeInterface;

/**
 * todo php 8 : fromValue with static return type + impacts on descendants
 * todo php x : replace with type generic Collection<StringValueObject>
 */
interface DateTimeValueObject
{
    public static function fromValue(DateTimeInterface $value): self;

    public function toValue(): DateTimeInterface;
}
