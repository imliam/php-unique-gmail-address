<?php

namespace ImLiam\UniqueGmailAddress\Rules;

class TagInUsername implements EmailRule
{
    protected string $separator;

    public function __construct(string $separator = '+')
    {
        $this->separator = $separator;
    }

    public function normalize(string $email): string
    {
        [$username, $domain] = explode('@', $email, 2);
        [$username, $tag] = explode($this->separator, $username, 2);

        return $username . '@' . $domain;
    }

    public function regex(string $email): string
    {
        [$username, $domain] = explode('@', $email, 2);

        return $username . '(' . preg_quote($this->separator) . '.*)?' . '@' . $domain;
    }
}
