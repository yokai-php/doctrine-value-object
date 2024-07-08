<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use DateTimeImmutable;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Yokai\DoctrineValueObject\Doctrine\Types;

final class EntityTest extends TestCase
{
    private const TYPES = [
        'user_status' => Status::class,
        'birthdate' => Birthdate::class,
        'phone_number' => PhoneNumber::class,
        'phone_numbers' => PhoneNumbers::class,
        'notifications' => Notifications::class,
    ];

    public function testUserWithNoValues(): void
    {
        $entityManager = $this->manager();

        // create an all null properties entity
        $user = new User();
        $entityManager->persist($user);
        $entityManager->flush();
        $entityManager->clear();

        // fetch row from database
        $sql = "SELECT * FROM user WHERE id = ?";
        /** @var array<string, string> $row */
        $row = $entityManager->getConnection()->fetchAssociative($sql, [$user->id]);
        // assert all columns are null
        self::assertNull($row['status'] ?? null);
        self::assertNull($row['birthdate'] ?? null);
        self::assertNull($row['contactPhoneNumber'] ?? null);
        self::assertNull($row['phoneNumbers'] ?? null);
        self::assertNull($row['notifications'] ?? null);

        // fetch entity from database
        /** @var User $user */
        $user = $entityManager->find(User::class, $user->id);
        // assert all properties are null
        self::assertNotNull($user);
        self::assertNull($user->status);
        self::assertNull($user->birthdate);
        self::assertNull($user->contactPhoneNumber);
        self::assertNull($user->phoneNumbers);
        self::assertNull($user->notifications);
    }

    public function testUserWithAllValues(): void
    {
        $entityManager = $this->manager();

        // create an all filled properties entity
        $user = new User();
        $user->status = Status::init();
        $user->birthdate = new Birthdate(new DateTimeImmutable('1986-11-17'));
        $user->contactPhoneNumber = new PhoneNumber('0677889900');
        $user->phoneNumbers = new PhoneNumbers(
            [new PhoneNumber('+33455667788'), new PhoneNumber('+33233445566')]
        );
        $user->notifications = new Notifications(true, false);
        // save changes
        $entityManager->persist($user);
        $entityManager->flush();
        $entityManager->clear();

        // fetch row from database
        $sql = "SELECT * FROM user WHERE id = ?";
        /** @var array<string, string> $row */
        $row = $entityManager->getConnection()->fetchAssociative($sql, [$user->id]);
        // assert all columns are filled with value object values
        self::assertSame('0', (string)($row['status'] ?? null));
        self::assertSame('1986-11-17 00:00:00', $row['birthdate'] ?? null);
        self::assertSame('+33677889900', $row['contactPhoneNumber'] ?? null);
        self::assertSame('["+33455667788","+33233445566"]', $row['phoneNumbers'] ?? null);
        self::assertSame('{"email":true,"sms":false}', $row['notifications'] ?? null);

        // fetch entity from database
        /** @var User $user */
        $user = $entityManager->find(User::class, $user->id);
        // assert all properties are hydrated to value objects
        self::assertNotNull($user);
        self::assertSame(0, $user->status?->getStatus());
        self::assertSame('1986-11-17', $user->birthdate?->getDate()->format('Y-m-d'));
        self::assertSame('+33677889900', $user->contactPhoneNumber?->getNumber());
        self::assertSame(
            ['+33455667788', '+33233445566'],
            \array_map(fn(PhoneNumber $number) => $number->getNumber(), $user->phoneNumbers?->getNumbers() ?? [])
        );
        self::assertTrue($user->notifications?->isEmail());
        self::assertFalse($user->notifications->isSms());
    }

    private function manager(): EntityManager
    {
        // register value object types
        (new Types(self::TYPES))->register(Type::getTypeRegistry());

        // create entity manager with an sqlite in memory storage
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__], true);
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'url' => 'sqlite:///:memory:'
        ]);
        $entityManager = new EntityManager($connection, $config);
        // create schema
        (new SchemaTool($entityManager))
            ->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}
