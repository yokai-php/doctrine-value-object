<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\IntegerValueObject;

final class IntegerValueObjectType extends Type
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
        return IntegerValueObject::class;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, $this->class);
        /** @var IntegerValueObject $value */

        return $this->getInheritedType()->convertToPHPValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?IntegerValueObject
    {
        $value = $this->getInheritedType()->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        Assert::integer($value);

        /** @var IntegerValueObject $object */
        $object = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($object, $this->class);

        return $object;
    }

    private function getInheritedType(): IntegerType
    {
        /** @var IntegerType $type */
        $type = $this->getType(Types::INTEGER);

        return $type;
    }
}
