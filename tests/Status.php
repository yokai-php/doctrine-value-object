<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Tests;

use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\IntegerValueObject;

class Status implements IntegerValueObject
{
    private const ANONYMOUS = 0;
    private const REGISTERED = 1;
    private const TRUSTED = 2;
    private const RELIABLE = 3;

    private const ALL = [
        self::ANONYMOUS,
        self::REGISTERED,
        self::TRUSTED,
        self::RELIABLE,
    ];

    private int $status;

    public function __construct(int $status)
    {
        Assert::oneOf($status, self::ALL);
        $this->status = $status;
    }

    public static function init(): self
    {
        return new self(self::ANONYMOUS);
    }

    public static function fromValue(int $value): IntegerValueObject
    {
        return new self($value);
    }

    public function toValue(): int
    {
        return $this->status;
    }

    public function promote(): self
    {
        if ($this->status === self::RELIABLE) {
            throw new \LogicException();
        }

        return new self(++$this->status);
    }

    public function demote(): self
    {
        if ($this->status === self::ANONYMOUS) {
            throw new \LogicException();
        }

        return new self(--$this->status);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function canComment(): bool
    {
        return $this->status >= self::REGISTERED;
    }

    public function canPost(): bool
    {
        return $this->status >= self::TRUSTED;
    }

    public function canVoteModeration(): bool
    {
        return $this->status >= self::RELIABLE;
    }

    public function canDecideModeration(): bool
    {
        return $this->status >= self::TRUSTED;
    }
}
