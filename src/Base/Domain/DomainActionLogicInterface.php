<?php

declare(strict_types=1);

namespace MagmaCore\Base\Domain;

interface DomainActionLogicInterface
{

    /**
     * Undocumented function
     *
     * @param Object $controller
     * @param string $entityObject
     * @param string $eventDispatcher
     * @param string $method
     * @param string $class
     * @param array $additionalContext
     * @return self
     */
    public function execute(
        Object $controller,
        string|null $entityObject = null,
        string|null $eventDispatcher = null,
        string $method,
        array $rules = [],
        array $additionalContext = []
    ): self;
    public function render(string|null $filename = null, int $extension = 2): self;
    public function with(array $context = []): self;
    public function form(Object $formRendering, string|null $formAction = null, mixed $data = null): self;
    public function table(array $tableParams = [], Object|Null $column = null, Object|Null $repository = null, array $tableData = []): self;
    public function end(): void;
}
