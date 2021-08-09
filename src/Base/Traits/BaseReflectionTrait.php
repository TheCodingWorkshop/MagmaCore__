<?php

declare(strict_types=1);

namespace MagmaCore\Base\Traits;

use ReflectionClassConstant;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

trait BaseReflectionTrait
{

    public function __construct()
    {
        defined('__REF_PROTECTED__') or define('__REF_PROTECTED__', ReflectionMethod::IS_PROTECTED);
        defined('__REF_PRIVATE__') or define('__REF_PRIVATE', ReflectionMethod::IS_PRIVATE);
        defined('__REF_PUBLIC__') or define('__REF_PUBLIC__', ReflectionMethod::IS_PUBLIC);
    }

    /**
     * @param mixed $class
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function reflection(string $name): self
    {
        if ($name) {
            $this->name = $name;
            $this->reflection = new ReflectionClass($name);
        }
        return $this;
    }

    public function hasMethod(string $name): ReflectionMethod|false
    {
        $has = $this->reflection->hasMethod($name);
        return is_bool($has) && $has === true ? $this->method($name) : false;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function method(?string $name = null): ReflectionMethod
    {
        return $this->reflection->getMethod($name);
    }

    /**
     * @param int|null $filters
     * @return iterable
     */
    public function methods(?int $filters = null): array
    {
        return $this->reflection->getMethods($filters);
    }

    public function namespace(): string
    {
        return $this->reflection->getNamespaceName();
    }

    /**
     * @return ReflectionClass|false
     */
    public function parent(): ReflectionClass|false
    {
        if (!false)
            return $this->reflection->getParentClass();
    }

    /**
     * @param int|null $filters
     * @return array
     */
    public function props(?int $filters = null): array
    {
        return $this->reflection->getProperties($filters);
    }

    public function hasProp(): ReflectionProperty|false
    {
        $has = $this->reflection->hasProperty($name);
        return is_bool($has) && $has === true ? $this->prop($name) : false;

    }

    /**
     * @param string $name
     * @return ReflectionProperty
     * @throws \ReflectionException
     */
    public function prop(string $name): ReflectionProperty
    {
        return $this->reflection->getProperty($name);
    }

    /**
     * @return ReflectionClassConstant|false
     */
    public function hasConst(): ReflectionClassConstant|false
    {
        $has = $this->reflection->hasConstant($name);
        return is_bool($has) && $has === true ? $this->const($name) : false;

    }

    /**
     * @param string $name
     * @return ReflectionClassConstant|false
     */
    public function const(string $name): ReflectionClassConstant|false
    {
        return $this->reflection->getConstant($name);
    }

    /**
     * @param int|null $filters
     * @return array
     */
    public function consts(?int $filters = null): array
    {
        return $this->reflection->getConstants($filters);
    }

    /**
     * @return string
     */
    public function className(): string
    {
        return $this->reflection->getShortName();
    }

    /**
     * @return string|false
     */
    public function comment(): string|false
    {
        return $this->reflection->getDocComment();
    }

    /**
     * @return array
     */
    public function interfaces(): array
    {
        return $this->reflection->getInterfaces();
    }

    /**
     * @return array
     */
    public function interfaceNames(): array
    {
        return $this->reflection->getInterfaceNames();
    }

    /**
     * Returns true if the reflection class is implementing the argument pass interface
     * @param string $interface
     * @return bool
     */
    public function implements(string $interface): bool
    {
        return $this->reflection->implementsInterface($this->reflection, $interface);
    }

    /**
     * @return string|false
     */
    public function fileName(): string|false
    {
        return $this->reflection->getFileName();
    }

    /**
     * @return int|false
     */
    public function startLine(): int|false
    {
        return $this->reflection->getStartLine();
    }

    /**
     * @return int|false
     */
    public function endLine(): int|false
    {
        return $this->reflection->getEndLine();
    }

    public function traits(): array
    {
        return $this->reflection->getTraits();
    }

    public function traitNames(): array
    {
        return $this->reflection->getTraitNames();
    }

    public function traitAlias()
    {
        return $this->reflection->getTraitAliases();
    }

    /**
     * @param string|null $name
     * @param int|null $flag
     * @return ReflectionAttribute
     */
    public function attr(?string $name = null, int $flag = 0): ReflectionAttribute|array
    {
        $this->attr = $this->reflection->getAttributes($name, $flag);
        return $this;
    }

    public function attrDump()
    {
        echo json_encode(array_map(fn(ReflectionAttribute $attr) => $attr->getName(), $this->attr));
    }

}