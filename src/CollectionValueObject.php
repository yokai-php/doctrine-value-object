<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject;

use Countable;
use IteratorAggregate;

/**
 * @template TKey
 * @template-covariant TValue
 * @template-extends IteratorAggregate<TKey, TValue>
 */
interface CollectionValueObject extends Countable, IteratorAggregate
{
    public static function fromValue(array $value): static;

    public function toValue(): array;
}
