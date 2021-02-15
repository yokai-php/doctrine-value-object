<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\TypeRegistry;

final class Types
{
    private const DOCTRINE_TYPES = [
        Types\CollectionValueObjectType::class,
        Types\DateTimeValueObjectType::class,
        Types\IntegerValueObjectType::class,
        Types\ObjectValueObjectType::class,
        Types\StringValueObjectType::class,
    ];

    private array $types;

    /**
     * @param array<string, class-string> $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function register(TypeRegistry $types): void
    {
        foreach ($this->types as $doctrineTypeName => $valueObjectType) {
            if (!$types->has($doctrineTypeName)) {
                $types->register($doctrineTypeName, $this->type($valueObjectType, $doctrineTypeName));
            }
        }
    }

    private function type(string $valueObjectType, string $doctrineTypeName): Type
    {
        foreach (self::DOCTRINE_TYPES as $doctrineTypeClass) {
            $supportValueObjectType = \call_user_func([$doctrineTypeClass, 'getSupportedValueObjectType']);
            if (\is_a($valueObjectType, $supportValueObjectType, true)) {
                return \call_user_func([$doctrineTypeClass, 'create'], $valueObjectType, $doctrineTypeName);
            }
        }

        throw new \InvalidArgumentException(
            \sprintf(
                'Value object named "%s" associated to type "%s" has no related doctrine type',
                $doctrineTypeName,
                $valueObjectType
            )
        );
    }
}
