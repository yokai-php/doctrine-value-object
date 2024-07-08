<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use PHPUnit\Framework\TestCase;

final class GenericTest extends TestCase
{
    public function test(): void
    {
        $phoneNumbers = new PhoneNumbers(
            [new PhoneNumber('+33455667788'), new PhoneNumber('+33233445566')],
        );

        foreach ($phoneNumbers as $phoneNumber) {
            self::assertSame('33', $phoneNumber->getCountry());
        }
    }
}
