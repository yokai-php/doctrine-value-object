<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="user_status", nullable=true)
     */
    public ?Status $status = null;

    /**
     * @ORM\Column(type="birthdate", nullable=true)
     */
    public ?Birthdate $birthdate = null;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     */
    public ?PhoneNumber $contactPhoneNumber = null;

    /**
     * @ORM\Column(type="phone_numbers", nullable=true)
     */
    public ?PhoneNumbers $phoneNumbers = null;

    /**
     * @ORM\Column(type="notifications", nullable=true)
     */
    public ?Notifications $notifications = null;
}
