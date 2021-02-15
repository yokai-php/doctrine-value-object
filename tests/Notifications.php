<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Yokai\DoctrineValueObject\ObjectValueObject;

final class Notifications implements ObjectValueObject
{
    private bool $email;
    private bool $sms;

    public function __construct(bool $email, bool $sms)
    {
        $this->email = $email;
        $this->sms = $sms;
    }

    public static function fromValue(array $value): ObjectValueObject
    {
        return new self(
            $value['email'] ?? false,
            $value['sms'] ?? false
        );
    }

    public function toValue(): array
    {
        return [
            'email' => $this->email,
            'sms' => $this->sms,
        ];
    }

    public function isEmail(): bool
    {
        return $this->email;
    }

    public function isSms(): bool
    {
        return $this->sms;
    }
}
