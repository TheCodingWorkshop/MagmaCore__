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

use MagmaCore\Utility\Stringify;
use MagmaCore\ValidationRule\ValidationRuleMethods;
use MagmaCore\ValidationRule\ValidationRuleInterface;
use MagmaCore\Base\Exception\BaseBadMethodCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class ValidationRule implements ValidationRuleInterface
{

    protected object $controller;
    protected object $validationClass;
    protected ValidationRuleMethods $validationRules;
    protected const ALLOWABLE_RULES = [
        'strings',
        'integre',
        'array',
        'object',
        'required',
        'unique',
        'equal'
    ];

    /**
     * Undocumented function
     *
     * @param ValidationRuleMethods $validationRuleFuncs
     * @return void
     */
    public function __construct(ValidationRuleMethods $validationRuleFuncs) {
        $this->validationRuleFuncs = $validationRuleFuncs;
    }

    /**
     * Undocumented function
     *
     * @param mixed $rule
     * @return void
     */
    public function addRule(mixed $rule): void
    {
        if ($rule)
            $this->rule = $this->resolvedRule($rule);
    }

    /**
     * Add additional object from the validation class which our validation rule methods
     * can use.
     *
     * @param string $controller
     * @param object $validationClass
     * @return void
     */
    public function addObject(string $controller, object $validationClass): void
    {
        if ($controller)
            $this->controller = new $controller([]);
        if ($validationClass)
            $this->validationClass = $validationClass;
    }

    /**
     * Resolve the array of possible rules pass from the validation class
     *
     * @param mixed $rule
     * @return mixed
     */
    private function resolvedRule(mixed $rule): mixed
    {
        if (is_string($rule)) {
            $rule = (string)$rule;
            /**
             * Explode the string and look for the pipe character that way we can separate 
             * our rules into callables
             */
            $rulePieces = $this->exploder($rule, '|');
            foreach ($rulePieces as $rulePiece) {
                $extractRuleWithArgs = $this->exploder($rulePiece);
                if (isset($extractRuleWithArgs) && count($extractRuleWithArgs) > 1) {
                    $this->throwInvalidRuleException($extractRuleWithArgs[0]);
                } else {
                    $this->throwInvalidRuleException($rulePiece);
                }
                return array_walk($rulePieces, function ($callback) {
                    if ($callback) {
                        list($method, $argument) = $this->resolveCallback($callback);
                        if (!method_exists($this->validationRuleFuncs, $method)) {
                            throw new BaseBadMethodCallException(
                                $method . '() does not exists within ' . __CLASS__
                            );
                        }
                        call_user_func_array(
                            array($this->validationRuleFuncs, $method),
                            [
                                $this->controller, 
                                $this->validationClass, 
                                $argument
                            ]
                        );
                    }
                });
            }
        }
    }

    /**
     * exploder helper which splits a string via the specified delimiter
     *
     * @param string $values
     * @param string $delimiter
     * @return array
     */
    public function exploder(string $values, string $delimiter = ':'): array
    {
        return explode($delimiter, $values);
    }

    /**
     * Resolve the callback. ie checks whether the rule has an argument. arguments
     * are defined after a colon. which we will explode by the callback argument. If 
     * a colon is defined then we can extract both method name and argument. else if a colon
     * wasn't define we will execute as normal. Return an array of the callback method name
     * any optional argument supplied with the rule.
     *
     * @param mixed $callback
     * @return mixed
     */
    private function resolveCallback(mixed $callback): mixed
    {
        if ($callback) {
            $stringify = new Stringify(); /* Call to the stringify utility class */
            $extract = $this->exploder($callback);
            if (isset($extract) && count($extract) > 1) { /* meaning if we have 2 elements */
                $validCallback = $stringify->camelCase($extract[0]);
                $args = (isset($extract[1]) ? $extract[1] : null);
            } else {
                $validCallback = $stringify->camelCase($callback);
                $args = null;
            }
            return [
                $validCallback,
                $args
            ];
        }
        return false;
    }

    /**
     * throw an exception if the passing invalid or unsupported rule
     *
     * @param mixed $rule
     * @return void
     */
    private function throwInvalidRuleException(mixed $rule): void
    {
        if (!in_array($rule, self::ALLOWABLE_RULES, true)) {
            throw new BaseInvalidArgumentException('Invalid validation rule ' . $rule);
        }
    }
}
