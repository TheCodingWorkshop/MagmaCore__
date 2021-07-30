<?php

declare(strict_types=1);

namespace MagmaCore\Base\Domain;

interface DomainActionLogicInterface
{

    /**
     * Undocumented function
     *
     * @param Object $controller
     * @param string|null $entityObject
     * @param string|null $eventDispatcher
     * @param string|null $objectSchema
     * @param string $method
     * @param array $rules
     * @param array $additionalContext
     * @param mixed $optional
     * @return self
     */
    public function execute(
        Object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = [],
        mixed $optional = null
    ): self;
    
    /**
     * Undocumented function
     *
     * @param string|null $filename
     * @param integer $extension
     * @return self
     */
    public function render(string|null $filename = null, int $extension = 2): self;

    /**
     * Undocumented function
     *
     * @param array $context
     * @return self
     */
    public function with(array $context = []): self;
    
    /**
     * Undocumented function
     *
     * @param Object $formRendering
     * @param string|null $formAction
     * @param mixed $data
     * @return self
     */
    public function form(Object $formRendering, string|null $formAction = null, mixed $data = null): self;

    /**
     * Undocumented function
     *
     * @param array $tableParams
     * @param object|null $column
     * @param object|null $repository
     * @param array $tableData
     * @return self
     */
    public function table(array $tableParams = [], object|null $column = null, object|null $repository = null, array $tableData = []): self;

    /**
     * Undocumented function
     *
     * @param string|null $type
     * @return void
     */
    public function end(string|null $type = null): void;
}
