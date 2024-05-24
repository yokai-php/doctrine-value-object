<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\CollectionValueObject;

final class CollectionValueObjectType extends Type
{
    use ValueObjectType;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getInheritedType()->getSQLDeclaration($column, $platform);
    }

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return CollectionValueObject::class;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, $this->class);
        /** @var CollectionValueObject $value */

        return $this->getInheritedType()->convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CollectionValueObject
    {
        $value = $this->getInheritedType()->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        Assert::isArray($value);

        /** @var CollectionValueObject $collection */
        $collection = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($collection, $this->class);

        return $collection;
    }

    private function getInheritedType(): JsonType
    {
        /** @var JsonType $type */
        $type = $this->getType(Types::JSON);

        return $type;
    }
}
