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

    protected object $object;
    protected object $controller;
    protected object $model;
    protected mixed $rule;
    protected const ALLOWABLE_RULES = [
        'strings',
        'integre',
        'array',
        'object',
        'required',
        'unique'
    ];

    /**
     * Undocumented function
     *
     * @param string $controller
     * @param object $validatorObject
     */
    public function __construct(string|null $controller = null, string|null $model = null, object|null $validatorObject = null)
    {
        $this->controller = new $controller([]);
        $this->object = $validatorObject;
        $this->model = ($model !==null) ? (new $model())->getRepo() : '';
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
            $this->rule = $this->resolved($rule);
    }

    /**
     * Undocumented function
     *
     * @param mixed $rule
     * @return mixed
     */
    private function resolved(mixed $rule): mixed
    {   
        if (is_string($rule)) {
            $rule = (string)$rule;
            /**
             * Explode the string and look for the pipe character that way we can separate 
             * our rules into callables
             */
            $rulePieces = explode('|', $rule);
            foreach ($rulePieces as $rulePiece) {
                if (!in_array($rulePiece, self::ALLOWABLE_RULES, true)) {
                    throw new BaseInvalidArgumentException('Invalid validation rule ' . implode(' ', $rulePieces));
                }
                return array_walk($rulePieces, function($callback) {
                    if ($callback) {
                        $validCallback = (new Stringify())->camelCase($callback);
                        if (!method_exists(new ValidationRuleMethods(), $callback)) {
                            throw new BaseBadMethodCallException(
                                $validCallback . '() does not exists within ' . __CLASS__
                            );
                        }
                        call_user_func_array(
                            array(new ValidationRuleMethods(), $validCallback),
                            [
                                $this->object->validateKey,
                                $this->object->validateValue,
                                $this->model,
                                $this->controller
                            ]
                        );

                    }
                });
            }
        }
    }



}