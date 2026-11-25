<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\TypeRegistry;
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

    private ?TypeRegistry $types = null;

    private ?Type $inheritedType = null;

    /**
     * @param class-string      $class
     * @param string            $name
     * @param TypeRegistry|null $types The registry to resolve the inherited type from
     *
     * @return self
     */
    public static function create(string $class, string $name, ?TypeRegistry $types = null): self
    {
        /** @var class-string $supportedType */
        $supportedType = static::getSupportedValueObjectType();
        Assert::classExists($class);
        Assert::isAOf($class, $supportedType);

        $type = new static();
        $type->class = $class;
        $type->name = $name;
        $type->types = $types;

        return $type;
    }

    /**
     * @return class-string
     */
    abstract public static function getSupportedValueObjectType(): string;

    abstract private static function getInheritedTypeName(): string;

    abstract private static function createInheritedType(): Type;

    /**
     * The DBAL type that this type wraps: the one registered under the inherited type name,
     * if any, so that an overridden type is honoured; resolved on first use,
     * because a type may be overridden after the value object types are registered.
     */
    private function getInheritedType(): Type
    {
        if ($this->inheritedType === null) {
            $name = self::getInheritedTypeName();
            $this->inheritedType = $this->types !== null && $this->types->has($name)
                ? $this->types->get($name)
                : self::createInheritedType();
        }

        return $this->inheritedType;
    }

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
