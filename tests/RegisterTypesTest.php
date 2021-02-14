<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Doctrine\DBAL\Types\Type;
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
        (new Types(['foo' => \stdClass::class]))->register(Type::getTypeRegistry());
    }
}
