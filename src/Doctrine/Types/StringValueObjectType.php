<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\StringValueObject;

final class StringValueObjectType extends StringType
{
    use ValueObjectType;

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return StringValueObject::class;
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
        /** @var StringValueObject $value */

        return parent::convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?StringValueObject
    {
        $value = parent::convertToPHPValue($value, $platform);
        if ($value === null) {
            return null;
        }

        Assert::string($value);

        /** @var StringValueObject $object */
        $object = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($object, $this->class);

        return $object;
    }
}
