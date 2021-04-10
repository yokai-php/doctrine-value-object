<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Webmozart\Assert\Assert;

trait ValueObjectType
{
    /**
     * @var class-string
     */
    private string $class;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param class-string $class
     * @param string       $name
     *
     * @return self
     */
    public static function create(string $class, string $name): self
    {
        /** @var class-string $supportedType */
        $supportedType = static::getSupportedValueObjectType();
        Assert::classExists($class);
        Assert::isAOf($class, $supportedType);

        $type = new static();
        $type->class = $class;
        $type->name = $name;

        return $type;
    }

    /**
     * @return class-string
     */
    abstract public static function getSupportedValueObjectType(): string;

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
