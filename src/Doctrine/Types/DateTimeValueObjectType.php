<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\DateTimeValueObject;

final class DateTimeValueObjectType extends DateTimeTzImmutableType
{
    use ValueObjectType;

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return DateTimeValueObject::class;
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
        /** @var DateTimeValueObject $value */

        return parent::convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeValueObject
    {
        $value = parent::convertToPHPValue($value, $platform);
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, DateTimeImmutable::class);

        /** @var DateTimeValueObject $object */
        $object = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($object, $this->class);

        return $object;
    }
}
