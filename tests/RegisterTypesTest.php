<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\TypeRegistry;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yokai\DoctrineValueObject\Doctrine\Types;

final class RegisterTypesTest extends TestCase
{
    public function testOnlyValueObjectsAreRegistered(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Value object named "foo" associated to type "stdClass" has no related doctrine type'
        );
        (new Types(['foo' => \stdClass::class]))->register(new TypeRegistry());
    }

    public function testInheritedTypeIsTheOneOfTheRegistry(): void
    {
        $registry = new TypeRegistry(['json' => self::customJsonType()]);
        (new Types(['phone_numbers' => PhoneNumbers::class]))->register($registry);

        self::assertSame(
            'CUSTOM_JSON',
            $registry->get('phone_numbers')->getSQLDeclaration([], $this->createMock(AbstractPlatform::class))
        );
    }

    public function testInheritedTypeOverriddenAfterRegistrationIsHonoured(): void
    {
        $registry = new TypeRegistry(['json' => new JsonType()]);
        (new Types(['phone_numbers' => PhoneNumbers::class]))->register($registry);
        $registry->override('json', self::customJsonType());

        self::assertSame(
            'CUSTOM_JSON',
            $registry->get('phone_numbers')->getSQLDeclaration([], $this->createMock(AbstractPlatform::class))
        );
    }

    private static function customJsonType(): JsonType
    {
        return new class extends JsonType {
            public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
            {
                return 'CUSTOM_JSON';
            }
        };
    }
}
