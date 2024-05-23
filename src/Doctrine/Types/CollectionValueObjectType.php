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
        /** @var JsonType $typeInherit */
        $typeInherit = $this->getType(Types::JSON);
        return $typeInherit->getSQLDeclaration($column, $platform);
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
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, $this->class);
        /** @var CollectionValueObject $value */

        /** @var JsonType $typeInherit */
        $typeInherit = $this->getType(Types::JSON);
        return $typeInherit->convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CollectionValueObject
    {
        /** @var JsonType $typeInherit */
        $typeInherit = $this->getType(Types::JSON);
        $value = $typeInherit->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        Assert::isArray($value);

        /** @var CollectionValueObject $collection */
        $collection = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($collection, $this->class);

        return $collection;
    }
}
