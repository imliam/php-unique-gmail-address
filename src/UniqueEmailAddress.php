<?php

namespace ImLiam\UniqueGmailAddress;

use ImLiam\UniqueGmailAddress\Rules\EmailRule;

class UniqueEmailAddress
{
    protected string $email;

    /** @var EmailRule[] */
    protected array $rules = [];

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function normalize(): string
    {
        return array_reduce($this->rules, function (string $email, EmailRule $rule) {
            return $rule->normalize($email);
        }, $this->email);
    }

    public function getRegexWithDelimiters()
    {
        return '/' . $this->getRegex() . '/';
    }

    public function getRegex()
    {
        $email = str_replace('@', '\\@', $this->normalize());

        return array_reduce($this->rules, function (string $email, EmailRule $rule) {
            return $rule->regex($email);
        }, $email);
    }

    public function matches($email)
    {
        /** @var array $matches */
        preg_match($this->getRegexWithDelimiters(), $email, $matches);

        return ! empty($matches);
    }
}
