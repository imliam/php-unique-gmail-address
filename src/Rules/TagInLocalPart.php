<?php

namespace ImLiam\UniqueGmailAddress\Rules;

class TagInLocalPart implements EmailRule
{
    protected string $separator;

    public function __construct(string $separator = '+')
    {
        $this->separator = $separator;
    }

    public function normalize(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);
        [$localPart, $tag] = explode($this->separator, $localPart, 2);

        return $localPart . '@' . $domain;
    }

    public function regex(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);

        return $localPart . '(' . preg_quote($this->separator) . '.*)?' . '@' . $domain;
    }
}
