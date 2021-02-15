<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\ObjectValueObject;

final class ObjectValueObjectType extends JsonType
{
    use ValueObjectType;

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return ObjectValueObject::class;
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
        /** @var ObjectValueObject $value */

        return parent::convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ObjectValueObject
    {
        $value = parent::convertToPHPValue($value, $platform);
        if ($value === null) {
            return null;
        }

        Assert::isArray($value);

        /** @var ObjectValueObject $collection */
        $collection = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($collection, $this->class);

        return $collection;
    }
}
