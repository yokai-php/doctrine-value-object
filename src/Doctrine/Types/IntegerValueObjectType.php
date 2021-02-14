<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\IntegerValueObject;

final class IntegerValueObjectType extends IntegerType
{
    use ValueObjectType;

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return IntegerValueObject::class;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, $this->class);
        /** @var IntegerValueObject $value */

        return parent::convertToPHPValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?IntegerValueObject
    {
        $value = parent::convertToPHPValue($value, $platform);
        if ($value === null) {
            return null;
        }

        Assert::integer($value);

        /** @var IntegerValueObject $object */
        $object = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($object, $this->class);

        return $object;
    }
}
