<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\DateTimeValueObject;

final class DateTimeValueObjectType extends Type
{
    use ValueObjectType;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var DateTimeTzImmutableType $typeInherit */
        $typeInherit = $this->getType(Types::DATETIMETZ_IMMUTABLE);
        return $typeInherit->getSQLDeclaration($column, $platform);
    }

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

        /** @var DateTimeTzImmutableType $typeInherit */
        $typeInherit = $this->getType(Types::DATETIMETZ_IMMUTABLE);
        return $typeInherit->convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?DateTimeValueObject
    {
        /** @var DateTimeTzImmutableType $typeInherit */
        $typeInherit = $this->getType(Types::DATETIMETZ_IMMUTABLE);
        $value = $typeInherit->convertToPHPValue($value, $platform);

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
