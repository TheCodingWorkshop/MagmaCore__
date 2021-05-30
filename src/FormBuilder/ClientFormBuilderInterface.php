<?php

declare(strict_types=1);

namespace MagmaCore\FormBuilder;

interface ClientFormBuilderInterface
{
    /**
     * Build the form ready for the view render. One argument required
     * which is the action where the form will be posted
     *
     * @param string $action - form action
     * @param object $dataRepository
     * @param object $callingController
     * @return string
     */
    public function createForm(string $action, ?Object $dataRepository = null, object $callingController = null);

}