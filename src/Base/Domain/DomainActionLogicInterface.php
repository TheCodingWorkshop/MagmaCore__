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
     * @param array $additionalContext
     * @return self
     */
    public function execute(
        Object $controller,
        string|null $entityObject = null,
        string|null $eventDispatcher = null,
        string $method,
        array $additionalContext = []
    ): self;
    public function render(string|null $filename = null, int $extension = 2): self;
    public function with(array $context = []): self;
    public function form(Object $formRendering, string|null $formAction = null): self;
    public function table(array $tableParams = [], array $tableData = []): self;
    public function end(): void;
}
