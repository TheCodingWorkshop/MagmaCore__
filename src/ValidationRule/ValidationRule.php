<?php

declare(strict_types=1);

namespace MagmaCore\ValidationRule;

class ValidationRule
{

    protected $currentObject;
    protected array $rule;
    protected const ALLOWABLE_RULES = [
        'string',
        'integre',
        'array',
        'object',
        'required',
        'unique'
    ];

    public function __construct()
    {
    }

    public function addRule(mixed $rule, object $object)
    {
        if ($rule)
            $this->rule = $this->resolved($rule);
        if ($object)
            $this->object = $object;
        
        return $this;
    }

    private function resolved($rule): void
    {   
        if (is_string($rule)) {
            $rule = (string)$rule;
            /**
             * Explode the string and look for the pipe character that way we can separate 
             * our rules into callables
             */
            $rulePieces = explode('|', $this->rule);
            foreach ($rulePieces as $rulePiece) {
                if (!in_array($rulePiece, self::ALLOWABLE_RULES, true)) {
                    throw new BaseInvalidArgumentException('Invalid validation rule ' . implode(' ', $rulePieces));
                }
                if (is_callable($rulePiece)) {
                    $this->func = call_user_func_array(array(ValidationRuleMethods, $rulePiece), [$key, $this->object])
                }
            }
        }
    }

    public function dispatchRule()
    {
        if ($error = new (error())) {
            if ($error) {
                list($error) = $this->func;
                $error->addError($error)->dispatchError()
            }
        }
    }


}