<?php

class Validator
{
    protected $rules = [];

    protected $input;

    public function __call($method, $argument)
    {
        $this->rules[] = [
            'object' => $this->getRule($method),
            'argument' => $argument,
        ];

        return $this;
    }

    public function withInput($input)
    {
        $this->input = $input;

        return $this;
    }

    public function isValid()
    {
        foreach ($this->rules as $rule) {
            if (!$rule['object']->isSatisfiedBy($this->input, $rule['argument'])) {
                return false;
            }
        }

        return true;
    }

    protected function getRule($rule)
    {
        return new $rule;
    }
}

class IsString
{
    public function isSatisfiedBy($input)
    {
        return is_string($input);
    }
}

class IsGreaterThan
{
    public function isSatisfiedBy($input, $argument)
    {
        return strlen($input) > $argument[0];
    }
}

$validator = new Validator;

$validator->isString()->isGreaterThan(3)->withInput(645645634);

var_dump($validator->isValid());