<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

use Countable;
use IteratorAggregate;

/**
 * todo php x : replace with type generic Collection<StringValueObject>
 */
interface CollectionValueObject extends Countable, IteratorAggregate
{
    public static function fromValue(array $value): static;

    public function toValue(): array;
}
