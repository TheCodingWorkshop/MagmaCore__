<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\ValidationRule;

interface ValidationRuleInterface
{

    /**
     * Undocumented function
     *
     * @param mixed $rule
     * @return void
     */
    public function addRule(mixed $rule): void;

    /**
     * Add additional object from the validation class which our validation rule methods
     * can use.
     *
     * @param string $controller
     * @param object $validationClass
     * @return void
     */
    public function addObject(string $controller, object $validationClass): void;



}